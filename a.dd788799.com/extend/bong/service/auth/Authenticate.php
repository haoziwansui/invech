<?php

namespace bong\service\auth;
use Closure;
use bong\service\auth\exception\JwtRefreshTokenException;

class Authenticate
{

    public function handle($request, Closure $next, ...$guards)
    {    	
        try{
            $this->authenticate($guards);
            $response = $next($request);
        }catch(JwtRefreshTokenException $e){            
            $response->header('Authorization', 'Bearer '.$e->getMessage());            
        }/*并不知道返回怎么样的json数据或重定向到哪里,其他异常不捕获,由上层处理;
        catch(\Exception $e){
            if($request->isAjax()||$request->module()=='api'){
                return json($e->getMessage(), 403);                
            }else{
                return redirect('index/login')->with(['errors'=>[$e->getMessage()],]);
            }            
        }*/
        return $response;
    }

    //多个guard,任何一个认证失败 就是认证失败
    protected function authenticate(array $guards)
    {
	
    	$auth = container('auth');

        if (empty($guards)) {
	       $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($auth->guard($guard)->authenticate()){
                $auth->shouldUse($guard);
            };
        }

    }
}


