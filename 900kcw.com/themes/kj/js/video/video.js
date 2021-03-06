var pubmethod = {},
    path = window.location.href,
    lotCode;
$(function () {
    pubmethod.init()
}), pubmethod.init = function () {
    if (-1 != path.indexOf("video_index") || void 0 != path.split("?")[1] && "" != path.split("?")[1]) {
        lotCode = path.split("?")[1];
        var e = pubmethod.tools.type(lotCode);
        void 0 != e && pubmethod.doAjax("", lotCode, e, !0)
    } else alert("外链代码有误，请重新获取代码！")
}, pubmethod.tools = {
    type: function (e) {
        for (var t = [
                ["cqnc", "10009"],
                ["xgc", "10048"],
                ["egxy", "10046"],
                ["gxklsf", "10038"],
                ["jsft", "10035"],
                ["twbg", "10047"],
                ["fcsd", "10041", "10043"],
                ["bjkl8", "10013", "10014"],
                ["klsf", "10005", "10011", "10034"],
                ["pk10", "10001", "10012", "10037"],
                ["qgc", "10039", "10040", "10042", "10044", "10045"],
                ["ssc", "10002", "10003", "10004", "10010", "10036"],
                ["kuai3", "10007", "10026", "10027", "10028", "10029", "10030", "10031", "10032", "10033"],
                ["shiyi5", "10006", "10008", "10015", "10016", "10017", "10018", "10019", "10020", "10021", "10022", "10023", "10024", "10025"]
            ], o = 0, r = t.length; o < r; o++)
            for (var s = 0, i = t[o].length; s < i; s++)
                if (e == t[o][s]) return t[o][0]
    },
    action: {
        pk10: "pks/getLotteryPksInfo.php",
        cqnc: "klsf/getLotteryInfo.php",
        ssc: "CQShiCai/getBaseCQShiCai.php",
        klsf: "klsf/getLotteryInfo.php",
        jsk3: "lotteryJSFastThree/getBaseJSFastThree.php",
        shiyi5: "ElevenFive/getElevenFiveInfo.php",
        bjkl8: "LuckTwenty/getBaseLuckTewnty.php",
        twbg: "LuckTwenty/getBaseLuckTewnty.php",
        egxy28: "LuckTwenty/getPcLucky28.php",
        gxklsf: "gxklsf/getLotteryInfo.php",
        kuai3: "lotteryJSFastThree/getBaseJSFastThree.php",
        fcsd: "QuanGuoCai/getLotteryInfo1.php",
        jsft: "pks/getLotteryPksInfo.php",
        xgc: ""
    },
    pageView: function (e) {
        return {
            cqnc: "video/cqnc/index.php",
            egxy: "video/PK10/video.php",
            gxklsf: "video/gxklsf_video/index.php",
            fcsd: "video/fc3DVideo/index.php",
            bjkl8: "video/bjkl8Video/index.php",
            twbg: "video/twbgVideo/twbg_index.php",
            klsf: "video/GDklsf/index.php",
            pk10: "video/PK10/video.php",
            qgc: "video/PK10/video.php",
            ssc: "video/SSC/index.php",
            kuai3: "video/kuai3_video/Kuai3.php",
            shiyi5: "video/11x5_video/index.php",
            jsft: "video/jisuft_video/index.php",
            xgc: "video/SixColor_animate/index.php"
        }[e]
    },
    random: function () {
        return (new Date).getTime()
    },
    ifObj: function (e) {
        var t = null;
        return "object" != typeof e ? t = JSON.parse(e) : (t = JSON.stringify(e), t = JSON.parse(t)), t
    },
    cutTime: function (e, t) {
        var o = e.replace("-", "/"),
            t = t.replace("-", "/");
        return o = o.replace("-", "/"), t = t.replace("-", "/"), (new Date(o) - new Date(t)) / 1e3
    }
}, pubmethod.repeatAjax = function (e, t) {
    setTimeout(function () {
        e(t)
    }, config.startTime)
}, pubmethod.doAjax = function (e, t, o, r) {
    var s = {
        url: pubmethod.tools.action[o],
        issue: e,
        lotCode: t,
        flag: r,
        type: o,
        succM: function (e, t) {
            pubmethod.creatHeadD[o](e, t)
        }
    };
    pubmethod.ajaxM(s)
}, pubmethod.ajaxM = function (e) {
    $.ajax({
        url: config.publicUrl + "" + e.url,
        type: "GET",
        async: !0,
        data: {
            issue: void 0 == e.issue ? "" : e.issue,
            lotCode: e.lotCode,
            datestr: ""
        },
        timeout: "6000",
        success: function (t) {
            try {
                e.succM(t, e)
            } catch (t) {
                pubmethod.repeatAjax(pubmethod.ajaxM, e)
            }
        },
        error: function (t) {
            pubmethod.repeatAjax(pubmethod.ajaxM, e)
        }
    })
}, pubmethod.creatHeadD = {
    pk10: function (e, t) {
        var o = pubmethod.tools.ifObj(e);
        if ("100002" == o.result.businessCode) throw new Error("error");
        if (0 == o.errorCode && 0 == o.result.businessCode) {
            o = o.result.data;
            for (var r = pubmethod.tools.cutTime(o.drawTime, o.serverTime), s = o.preDrawCode.split(","), i = "", u = 0, n = s.length; u < n; u++) "0" == s[u].substr(0, 1) ? i += s[u].substr(1, 1) + "," : i += s[u] + ",";
            if (r = r < 0 ? 1 : r, showcurrentresult(i), $("#currentdrawid").text(o.drawCount), $("#nextdrawid").text(o.preDrawIssue), $("#stat1_1").text(o.sumFS), $("#stat1_2").text("0" == o.sumBigSamll ? "大" : "小"), $("#stat1_3").text("0" == o.sumSingleDouble ? "单" : "双"), $("#stat2_1").text("0" == o.firstDT ? "龙" : "虎"), $("#stat2_2").text("0" == o.secondDT ? "龙" : "虎"), $("#stat2_3").text("0" == o.thirdDT ? "龙" : "虎"), $("#stat2_4").text("0" == o.fourthDT ? "龙" : "虎"), $("#stat2_5").text("0" == o.fifthDT ? "龙" : "虎"), t.flag) $("#hlogo").find("img").attr("src", "images/logo/logo-" + t.lotCode + ".png"), $(".statuslogo").css({
                background: "url(images/logo/logo-" + t.lotCode + ".png)no-repeat"
            }), startcountdown(r, t);
            else {
                if (!t.flag && r <= 1) throw new Error("error");
                setTimeout(function () {
                    finishgame(i)
                }, "1000"), setTimeout(function () {
                    startcountdown(r - 11, t)
                }, "10000")
            }
        }
    },
    cqnc: function (e, t) {
        var o = pubmethod.tools.ifObj(e);
        if ("100002" == o.result.businessCode) throw new Error("error");
        if (0 == o.errorCode && 0 == o.result.businessCode) {
            o = o.result.data;
            for (var r = pubmethod.tools.cutTime(o.drawTime, o.serverTime), s = o.preDrawCode.split(","), i = [], u = 0, n = s.length; u < n; u++) "0" == s[u].substr(0, 1) ? i.push(s[u].substr(1, 1)) : i.push(s[u]);
            if (r = r < 0 ? 1 : r, t.flag) cqncVideo.statusFun(o.preDrawIssue, i, r, !0, t);
            else {
                if (!t.flag && r <= 1) throw new Error("error");
                setTimeout(function () {
                    stopanimate(i, r, t)
                }, "1000")
            }
        }
    },
    ssc: function (e, t) {
        if ("100002" == (l = pubmethod.tools.ifObj(e)).result.businessCode) throw new Error("error");
        if (0 == l.errorCode && 0 == l.result.businessCode) {
            l = l.result.data;
            for (var o = pubmethod.tools.cutTime(l.drawTime, l.serverTime), r = l.preDrawCode.split(","), s = [], i = 0, u = r.length; i < u; i++) "0" == r[i].substr(0, 1) && r[i].length > 1 ? s.push(r[i].substr(1, 1)) : s.push(r[i]);
            o = o < 0 ? 1 : o;
            var n = "";
            "0" == l.dragonTiger ? n = "龙" : "1" == l.dragonTiger ? n = "虎" : "2" == l.dragonTiger && (n = "和");
            var l = {
                preDrawCode: s,
                id: "#numBig",
                counttime: o,
                preDrawIssue: l.preDrawIssue,
                drawTime: l.drawTime.substr(l.drawTime.length - 8, 8),
                sumNum: l.sumNum,
                sumSingleDouble: 0 == l.sumSingleDouble ? "单" : "双",
                sumBigSmall: 0 == l.sumBigSmall ? "大" : "小",
                dragonTiger: n
            };
            if (t.flag) sscAnimateEnd(l, t), $("#hlogo").find("img").attr("src", "img/cqssc/logo-" + t.lotCode + ".png");
            else {
                if (!t.flag && o <= 1) throw new Error("error");
                setTimeout(function () {
                    sscAnimateEnd(l, t)
                }, "1000")
            }
        }
    },
    shiyi5: function (e, t) {
        var o = pubmethod.tools.ifObj(e);
        if (console.log(o), "100002" == o.result.businessCode) throw new Error("error");
        if (0 == o.errorCode && 0 == o.result.businessCode) {
            o = o.result.data;
            for (var r = pubmethod.tools.cutTime(o.drawTime, o.serverTime), s = o.preDrawCode.split(","), i = [], u = 0, n = s.length; u < n; u++) "0" == s[u].substr(0, 1) ? i.push(1 * s[u].substr(1, 1)) : i.push(1 * s[u]);
            if (r = r < 0 ? 1 : r, console.log(i), t.flag) $(".nameLogo").find("img").attr("src", "img/logo/11x5_" + t.lotCode + ".png"), k3v.startVideo(o, t), console.log($(".nameLogo"), t);
            else {
                if (!t.flag && r <= 1) throw new Error("error");
                console.log(o), setTimeout(function () {
                    k3v.stopVideo(o, t)
                }, "1000")
            }
        }
    },
    klsf: function (e, t) {
        var o = pubmethod.tools.ifObj(e);
        if ("100002" == o.result.businessCode) throw new Error("error");
        if (0 == o.errorCode && 0 == o.result.businessCode) {
            o = o.result.data;
            for (var r = pubmethod.tools.cutTime(o.drawTime, o.serverTime), s = o.preDrawCode.split(","), i = [], u = 0, n = s.length; u < n; u++) "0" == s[u].substr(0, 1) ? i.push(1 * s[u].substr(1, 1)) : i.push(1 * s[u]);
            r = r < 0 ? 1 : r;
            var l = o.preDrawIssue,
                a = o.drawIssue,
                d = o.drawTime.split(" ")[1].slice(0, 5);
            if (t.flag) $(".video_box").css("background", "url(img/logo/" + t.lotCode + ".jpg) 0 0 no-repeat"), fun.fillHtml(l, a, d, r, i, t);
            else {
                if (!t.flag && r <= 1) throw new Error("error");
                setTimeout(function () {
                    fun.Trueresult(i), fun.fillHtml(l, a, d, r, void 0, t)
                }, "1000")
            }
        }
    },
    kuai3: function (e, t) {
        var o = pubmethod.tools.ifObj(e);
        if ("100002" == o.result.businessCode) throw new Error("error");
        if (0 == o.errorCode && 0 == o.result.businessCode) {
            o = o.result.data;
            for (var r = pubmethod.tools.cutTime(o.drawTime, o.serverTime), s = o.preDrawCode.split(","), i = [], u = 0, n = s.length; u < n; u++) "0" == s[u].substr(0, 1) ? i.push(1 * s[u].substr(1, 1)) : i.push(1 * s[u]);
            r = r < 0 ? 1 : r;
            o.drawTime.split(" ")[1].slice(0, 5);
            console.log(o);
            var l = {
                seconds: r,
                preDrawCode: i,
                sumNum: o.sumNum,
                drawTime: o.drawTime,
                drawIssue: o.drawIssue,
                preDrawIssue: o.preDrawIssue
            };
            if (t.flag) $(".nameLogo").find("img").attr("src", "img/logo/" + t.lotCode + ".png"), k3v.stopVideo(l, t);
            else {
                if (!t.flag && r <= 1) throw new Error("error");
                setTimeout(function () {
                    k3v.stopVideo(l, t)
                }, "1000")
            }
        }
    },
    fcsd: function (e, t) {
        var o = pubmethod.tools.ifObj(e);
        if ("100002" == o.result.businessCode) throw new Error("error");
        if (0 == o.errorCode && 0 == o.result.businessCode) {
            o = o.result.data;
            for (var r = pubmethod.tools.cutTime(o.drawTime, o.serverTime), s = o.preDrawCode.split(","), i = [], u = 0, n = s.length; u < n; u++) "0" == s[u].substr(0, 1) ? i.push(1 * s[u].substr(1, 1)) : i.push(1 * s[u]);
            r = r < 0 ? 1 : r, o.cutime = r;
            o.drawTime.split(" ")[1];
            if (console.log(o), o.preDrawCode = i, t.flag) $(".logo").css("background", "url(img/logo/" + t.lotCode + ".png) center center no-repeat"), fcsdv.startVid(o, t);
            else {
                if (t.flag && r <= 1) throw new Error("error");
                setTimeout(function () {
                    fcsdv.stopVid(o, t)
                }, "1000")
            }
        }
    },
    bjkl8: function (e, t) {
        var o = pubmethod.tools.ifObj(e);
        if ("100002" == o.result.businessCode) throw new Error("error");
        if (0 == o.errorCode && 0 == o.result.businessCode) {
            o = o.result.data;
            for (var r = pubmethod.tools.cutTime(o.drawTime, o.serverTime), s = o.preDrawCode.split(","), i = [], u = 0, n = s.length; u < n; u++) "0" == s[u].substr(0, 1) ? i.push(1 * s[u].substr(1, 1)) : i.push(1 * s[u]);
            if (r = r < 0 ? 1 : r, o.cutime = r, console.log(o), o.preDrawCode = i, t.flag) $(".logo").css("background", "url(img/logo/" + t.lotCode + ".png) center center no-repeat"), syxwV.startVid(o, t);
            else {
                if (!t.flag && r <= 1) throw new Error("error");
                setTimeout(function () {
                    syxwV.stopVid(o, t)
                }, "1000")
            }
        }
    },
    twbg: function (e, t) {
        var o = pubmethod.tools.ifObj(e);
        if ("100002" == o.result.businessCode) throw new Error("error");
        if (0 == o.errorCode && 0 == o.result.businessCode) {
            o = o.result.data;
            for (var r = pubmethod.tools.cutTime(o.drawTime, o.serverTime), s = o.preDrawCode.split(","), i = [], u = 0, n = s.length; u < n; u++) "0" == s[u].substr(0, 1) ? i.push(1 * s[u].substr(1, 1)) : i.push(1 * s[u]);
            if (r = r < 0 ? 1 : r, o.cutime = r, console.log(o), o.preDrawCode = i, t.flag) syxwV.startVid(o, t);
            else {
                if (!t.flag && r <= 1) throw new Error("error");
                setTimeout(function () {
                    syxwV.stopVid(o, t)
                }, "1000")
            }
        }
    },
    gxklsf: function (e, t) {
        var o = pubmethod.tools.ifObj(e);
        if ("100002" == o.result.businessCode) throw new Error("error");
        if (0 == o.errorCode && 0 == o.result.businessCode) {
            o = o.result.data;
            for (var r = pubmethod.tools.cutTime(o.drawTime, o.serverTime), s = o.preDrawCode.split(","), i = [], u = 0, n = s.length; u < n; u++) "0" == s[u].substr(0, 1) ? i.push(1 * s[u].substr(1, 1)) : i.push(1 * s[u]);
            if (r = r < 0 ? 1 : r, o.cutime = r, console.log(o), o.numArr = i, t.flag) gxklsf.startVid(o, t);
            else {
                if (!t.flag && r <= 1) throw new Error("error");
                setTimeout(function () {
                    gxklsf.stopVid(o, t)
                }, "1000")
            }
        }
    },
    jsft: function (e, t) {
        var o = pubmethod.tools.ifObj(e);
        if ("100002" == o.result.businessCode) throw new Error("error");
        if (0 == o.errorCode && 0 == o.result.businessCode) {
            o = o.result.data;
            for (var r = pubmethod.tools.cutTime(o.drawTime, o.serverTime), s = o.preDrawCode.split(","), i = [], u = 0, n = s.length; u < n; u++) "0" == s[u].substr(0, 1) ? i.push(1 * s[u].substr(1, 1)) : i.push(1 * s[u]);
            if (r = r < 0 ? 1 : r, o.cutime = r, console.log(o), showcurrentresult(o.preDrawCode), $("#currentdrawid").text(o.drawCount), $("#nextdrawid").text(o.preDrawIssue), $("#stat1_1").text(o.sumFS), $("#stat1_2").text("0" == o.sumBigSamll ? "大" : "小"), $("#stat1_3").text("0" == o.sumSingleDouble ? "单" : "双"), $("#stat2_1").text("0" == o.firstDT ? "龙" : "虎"), $("#stat2_2").text("0" == o.secondDT ? "龙" : "虎"), $("#stat2_3").text("0" == o.thirdDT ? "龙" : "虎"), $("#stat2_4").text("0" == o.fourthDT ? "龙" : "虎"), $("#stat2_5").text("0" == o.fifthDT ? "龙" : "虎"), t.flag) startcountdown(r, t);
            else {
                if (!t.flag && r <= 1) throw new Error("error");
                setTimeout(function () {
                    finishgame(i.toString())
                }, "1000"), setTimeout(function () {
                    startcountdown(r - 11, t)
                }, "10000")
            }
        }
    }
};