<?php

namespace bong\service;
use ReflectionClass;
use bong\service\queue\Contracts\ShouldQueue;
use bong\service\queue\CallQueuedListener;
use bong\service\broadcast\Contracts\ShouldBroadcast;

class EventDispatcher
{

    protected $listeners = [];

    public function __construct()
    {

    }

    public function listen($events, $listener)
    {
        foreach ((array) $events as $event) {
            $this->listeners[$event][] = $this->makeListener($listener);
        }
    }

    public function makeListener($listener)
    {
        if (is_string($listener)) {
            return $this->createClassListener($listener);
        }

        return function ($event, $payload) use ($listener) {
            return $listener(...array_values($payload));
        };
    }

    public function createClassListener($listener)
    {
        return function ($event, $payload) use ($listener) {
            return call_user_func_array(
                $this->createClassCallable($listener), $payload
            );            
        };
    }    

    protected function createClassCallable($listener)
    {
        list($class, $method) = parse_callback($listener,'handle');

        if ($this->handlerShouldBeQueued($class)) {
            return $this->createQueuedHandlerCallable($class, $method);
        } else {
            $obj = container($class);
            return [$obj, $method];
        }
    }

    protected function handlerShouldBeQueued($class)
    {
        try {
            return (new ReflectionClass($class))->implementsInterface(
                ShouldQueue::class
            );
        } catch (Exception $e) {
            return false;
        }
    }

    protected function createQueuedHandlerCallable($class, $method)
    {
        return function () use ($class, $method) {
            $arguments = array_map(function ($a) {
                return is_object($a) ? clone $a : $a;
            }, func_get_args());

            $this->queueHandler($class, $method, $arguments);
        };
    } 

    protected function queueHandler($class, $method, $arguments)
    {
        $listener = (new ReflectionClass($class))->newInstanceWithoutConstructor();
        $queue = $listener->queue ?? null;

        $queued_listener = new CallQueuedListener($class, $method, $arguments);

        $queue = container('queue')->pushOn($queue,$queued_listener);
    }    


    public function dispatch($event, $payload = [], $halt = false)
    {
        list($event, $payload) = $this->parseEventAndPayload(
            $event, $payload
        );

        if ($this->shouldBroadcast($payload)) {
            $this->broadcastEvent($payload[0]);
        }

        $responses = [];

        foreach ($this->getListeners($event) as $listener) {
            $response = $listener($event, $payload);

            if ($halt && ! is_null($response)) {
                return $response;
            }

            if ($response === false) {
                break;
            }

            $responses[] = $response;
        }

        return $halt ? null : $responses;
    }

    protected function parseEventAndPayload($event, $payload)
    {
        if (is_object($event)) {
            list($payload, $event) = [[$event], get_class($event)];
        }

        $payload = ! is_array($payload) ? [$payload] : $payload;

        return [$event, $payload];
    }    

    public function getListeners($eventName)
    {
        $listeners = $this->listeners[$eventName] ?? [];

        return class_exists($eventName, false)
                    ? $this->addInterfaceListeners($eventName, $listeners)
                    : $listeners;
    }

    protected function addInterfaceListeners($eventName, array $listeners = [])
    {
        foreach (class_implements($eventName) as $interface) {
            if (isset($this->listeners[$interface])) {
                foreach ($this->listeners[$interface] as $names) {
                    $listeners = array_merge($listeners, (array) $names);
                }
            }
        }

        return $listeners;
    }


    protected function shouldBroadcast(array $payload)
    {
        return isset($payload[0]) &&
               $payload[0] instanceof ShouldBroadcast;
    }

    protected function broadcastEvent($event)
    {
        container('broadcast')->queue($event);
    }
}
