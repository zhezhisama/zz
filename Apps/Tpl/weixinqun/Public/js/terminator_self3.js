(function (b)
{
    var d = KClass.create(
    {
        init : function (g)
        {
            this.opt = 
            {
                id : "focus", tabTn : "li", conTn : "div", textTn : "", current : 0, auto : 0, direction : 1, 
                eType : "click", curCn : "current", z : 100, fxspeed : 1000, interval : 2000, tempCn : [], 
                effect : "def", pageBt : false, ipNewPage : false, fxOption : {},
                scrOption : {}, tabOption : {}
            };
            b.extend(this.opt, g);
            var i = this;
            var h = this.opt;
            this.current = h.current;
            this.mousecurrent = h.mousecurrent;
            this.slidecurrent = h.slidecurrent;
            this.fxspeed = h.fxOption.speed || 500;
            this.fxeffect = h.fxOption.magic || "";
            this.scrstep = h.scrOption.step || 1;
            this.screffect = h.scrOption.magic || "";
            this.scrcol = h.scrOption.scrcolumn || ".row1";
            this.showcolclass = h.scrOption.showcolumn || ".showcolumn";
            this.shownum = h.tabOption.shownum || 4;
            this.tabwr = h.tabOption.tabwr;
            this.tabnum = h.tabOption.tabnum;
            this.othertabTn = h.otherTn || "";
            this.fadetime = 50;
            this.to = 0;
            this.cur = 0;
            this.oneFinger = false;
            this.fMoveX = 0;
            this.tevent = null;
            this.conId = h.conId ? h.conId : h.id;
            this.tabId = h.tabId ? h.tabId : h.id;
            this.textId = h.textId ? h.textId : h.id;
            this.mbox = b(h.id).node;
            this.cont = b(h.conId).node;
            this.lock = 0;
            this.lock2 = 0;
            this.flipscrolling = 0;
            this.cons = h.conCn ? b(this.conId).find(h.conCn) : b(this.conId).find(h.conTn);
            this.scrcont = b(this.conId).find(this.scrcol);
            this.levels = this.cons.len;
            this.tabs = h.tabCn ? b(this.tabId).find(h.tabCn) : b(this.tabId).find(h.tabTn);
            this.texts = h.textCn ? b(this.textId).find(h.textCn) : b(this.textId).find(h.textTn);
            this.tabitemlen = this.tabs.item(0).node.offsetWidth;
            this.effectFn = h.conId && h.effect && this.effects[h.effect] ? this.effects[h.effect] : this.effects.def;
            if (i.tabwr)
            {
                i.tabobj = b(i.opt.id).find(i.tabwr).item(0);
                i.y = i.tabobj.scrollLeft();
                i.tn = Math.floor(this.levels / this.tabnum) + 1;
                i.temptn = i.tn;
                i.sn = 1
            }
            if (h.effect == "slide" || h.effect == "fxslide")
            {
                this.cont.style.position = "absolute";
                this.Tol = h.vertical == "top" ? "top" : "left";
                this.ml = this.cons.item(0).node[h.vertical == "top" ? "offsetHeight" : "offsetWidth"]
            }
            else
            {
                if (h.effect == "scroll")
                {
                    this.cont.style.position = "absolute";
                    this.Tol = h.vertical == "top" ? "top" : "left";
                    this.ml = (this.cons.item(0).node[h.vertical == "top" ? "offsetHeight" : "offsetWidth"]);
                    b(h.id).find(this.showcolclass).item(0).setStyle({
                        width : this.ml + "px", overflow : "hidden"
                    });
                    b(h.id).setStyle({
                        width : this.ml + 5 + "px"
                    });
                    b(this.conId).append(this.scrcont.item(0).clone(true));
                    this.levels = b(this.conId).find(h.conCn).len;
                    this.total = (this.levels - 1) * this.ml
                }
                else
                {
                    if (h.effect == "fade")
                    {
                        if (!h.pageBt) {
                            this.cons.setStyle({
                                position : "absolute", top : 0
                            });
                            i.tabs.opacity(0.5)
                        }
                    }
                }
            }
            this.nd = h.extra ? e(h.extra[1]) : null;
            if (!this.effects.scroll && this.tabs.len != this.cons.len) {
                throw new Error("Match Failed");
                return
            }
            if (h.effect != "scroll")
            {
                this.tabs.each(function (k, j)
                {
                    i.tevent = k.bind("_" + h.eType, function ()
                    {
                        i.fixMoveTo(j);
                        i.timeManger1(i)
                    })
                })
            }
            if (h.eType == "mouseover") {
                b(this.mbox).hover(function ()
                {
                    i.timeManger1(i)
                },
                function ()
                {
                    i.timeManger2(i)
                })
            }
            else
            {
                if (h.eType == "click")
                {
                    b(this.mbox).bind("click", function ()
                    {
                        i.timeManger1(i)
                    });
                    b(this.mbox).bind("mouseout", function ()
                    {
                        i.timeManger2(i)
                    })
                }
                else
                {
                    if (h.effect == "flip")
                    {
                        this.tabs.each(function (k, j)
                        {
                            k.bind("mouseout", function ()
                            {
                                i.runId && clearTimeout(i.runId);
                                i.flipscrolling = 1;
                                i.opt.auto && (i.runId = setTimeout(function ()
                                {
                                    i.next()
                                },
                                i.opt.interval))
                            })
                        })
                    }
                }
            }
            var f = b(this.conId);
            i.th1 = null;
            i.th2 = 1;
            i.th3 = 0;
            f.bind("_touchstart", function ()
            {
                i.tochStart(event)
            });
            b().bind("gesturestart", function ()
            {
                f.unbind("_touchstart", i.th1);
                i.th2 = 1;
            });
            b().bind("gestureend", function ()
            {
                i.th2 = 0;
            });
            f.bind("touchend", function ()
            {
                i.th1 = f.bind("_touchstart", function ()
                {
                    i.tochSE(event)
                });
                i.th2 = 0;
            });
            this.cons.each(function (m, j)
            {
                if (!h.tabCn)
                {
                    var k = m.find("div").item(0);
                    k.bind("touchend", function ()
                    {
                        i.tochEnd(event, k);
                        i.th2 = 1;
                    })
                }
                else {
                    m.bind("touchend", function ()
                    {
                        i.tochEnd(event, 0);
                        i.th2 = 1;
                    })
                }
            });
            if (h.bns && h.bns.length == 2)
            {
                b(h.id).find(h.bns[0]).item(0).click(function ()
                {
                    if (h.effect == "scroll" && !i.tabwr) {
                        i.scrprev()
                    }
                    else {
                        if (h.effect && i.tabwr) {
                            i.tabprev(i)
                        }
                        else {
                            i.prev()
                        }
                    }
                });
                b(h.id).find(h.bns[1]).item(0).click(function ()
                {
                    if (h.effect == "scroll" && !i.tabwr) {
                        i.scrnext()
                    }
                    else {
                        if (h.effect && i.tabwr) {
                            i.tabnext(i)
                        }
                        else {
                            i.next()
                        }
                    }
                })
            }
            if (h.effect == "scroll") {
                i.scrTo(this.current)
            }
            else {
                i.moveTo(this.current)
            }
            if (h.totalId) {
                b(h.totalId).html(this.levels)
            }
        },
        linkTo : function (f)
        {
            if (this.opt.ipNewPage) {
                window.open(f.parent().node.href, "_blank")
            }
            else {
                window.location.href = f.parent().node.href;
            }
        },
        linkBl : function (f)
        {
            window.location.href = f;
        },
        tochStart : function (f)
        {
            f.stopImmediatePropagation();
            if (!this.th2) {
                f.preventDefault()
            }
            if (f.targetTouches.length > 1) {
                this.oneFinger = false;
                return
            }
            else {
                this.oneFinger = true
            }
            this.fMoveX = f.changedTouches[0].pageX;
            this.th2 = 1;
        },
        tochSE : function (f)
        {
            f.preventDefault()
        },
        tochEnd : function (i, g)
        {
            var h = i.target;
            if (h.href) {
                var f = h.href
            }
            this.th2 = 1;
            if (!this.oneFinger) {
                return
            }
            if (this.fMoveX < i.changedTouches[0].pageX) {
                this.tcTime();
                this.prev()
            }
            else
            {
                if (this.fMoveX > i.changedTouches[0].pageX) {
                    this.tcTime();
                    this.next()
                }
                else {
                    if (!g) {
                        if (f) {
                            this.linkBl(f)
                        }
                        else {
                            return
                        }
                    }
                    else {
                        return this.linkTo(g);
                    }
                }
            }
        },
        effects : 
        {
            def : function (f, g)
            {
                f.cons.setStyle("display", "none");
                f.cons.item(g).setStyle("display", "block");
                f.opt.auto && (f.runId = setTimeout(function ()
                {
                    f.next()
                },
                f.opt.interval))
            },
            fxslide : function (f, h, g)
            {
                if (f.opt.vertical == "top") {
                    b(f.cont).go({
                        top : g + "px"
                    },
                    f.fxspeed, f.fxeffect).delay(100)
                }
                else
                {
                    if (f.opt.vertical == "left") {
                        b(f.cont).go({
                            left : g + "px"
                        },
                        f.fxspeed, f.fxeffect).delay(10)
                    }
                }
                f.opt.auto && (f.runId = setTimeout(function ()
                {
                    f.next()
                },
                f.opt.interval))
            },
            scroll : function (f, h, g)
            {
                if (f.opt.vertical == "top") {
                    b(f.cont).go({
                        top : g + "px"
                    },
                    f.fxspeed, f.fxeffect).delay(10)
                }
                else
                {
                    if (f.opt.vertical == "left")
                    {
                        if (h == 3)
                        {
                            b(f.cont).go({
                                left : g + "px"
                            },
                            f.fxspeed, f.fxeffect, function ()
                            {
                                b(f.cont).node.style.left = "0px";
                            })
                        }
                        else {
                            b(f.cont).go({
                                left : g + "px"
                            },
                            f.fxspeed, f.fxeffect).delay(10)
                        }
                    }
                }
                f.opt.auto && (f.runId = setTimeout(function ()
                {
                    f.scrnext()
                },
                f.opt.interval))
            },
            slide : function (f, i, h)
            {
                var g = parseInt(f.cont.style[f.Tol] ? f.cont.style[f.Tol] : "0px");
                f.end = (g == h) ? 1 : 0;
                if (!f.end)
                {
                    if (g < h) {
                        g += Math.ceil((h - g) / 10)
                    }
                    if (g > h) {
                        g -= Math.ceil((g - h) / 10)
                    }
                    f.cont.style[f.Tol] = g + "px";
                    f.runId = setTimeout(function ()
                    {
                        f.effectFn(f, i, h)
                    }, 10)
                }
                else {
                    f.opt.auto && (f.runId = setTimeout(function ()
                    {
                        f.next()
                    },
                    f.opt.interval))
                }
            },
            fade : function (h, i)
            {
                var g = 90;
                h.cons.setStyle("display", "none");
                h.cons.item(i).setStyle("display", "block");
                var f = h.cons.item(i).find("div").nitem(0);
                (function ()
                {
                    h.runffId && clearTimeout(h.runffId);
                    if (g > 0) {
                        g =g-4;
                        c(f, g);
                        h.runffId = setTimeout(arguments.callee, h.fadetime)
                    }
                    else {
                        return true;
                    }
                })()
            },
            flip : function (i, j)
            {
                if (i.flipscrolling) {
                    return false
                }
                var p = function (f)
                {
                    if (f && f.indexOf("#") ==- 1 && f.indexOf("(") ==- 1) {
                        return "rgb(" + l[f].toString() + ")"
                    }
                    else {
                        return f;
                    }
                };
                var y = function ()
                {
                    var f = q.node.innerHTML;
                    return f;
                };
                var k = function ()
                {
                    w.css("visibility", "hidden").html("").css(
                    {
                        visibility : "visible", position : "absolute", left : u.left + "px", top : u.top + "px", 
                        margin : 0, zIndex : 9999
                    });
                    w.ready = 1;
                    b(i.conId).append(w)
                };
                i.cons.setStyle("display", "none");
                i.cons.item(j).setStyle("display", "block");
                var x = i.cons.item(j), q = i.cons.item(j + 1), u, B, z, y;
                var w = x.clone(true);
                var v = 
                {
                    direction : (function (f)
                    {
                        switch (f)
                        {
                            case "tb":
                                return "bt";
                            case "bt":
                                return "tb";
                            case "lr":
                                return "rl";
                            case "rl":
                                return "lr";
                            default:
                                return "bt";
                        }
                    })(i.opt.direction), content : x.html(), speed : i.speed || 500, onBefore : i.onBefore || function () {},
                    onEnd : i.onEnd || function () {}, onAnimation : i.onAnimation || function () {}
                };
                u = 
                {
                    width : x.width(), height : x.height(), fontSize : x.css("font-size") || "12px", direction : i.opt.direction || "tb", 
                    bgColor : p(i.opt.fromcolor) || x.css("background-color"), toColor : p(i.opt.tocolor) || "red", 
                    speed : i.opt.speed || 1000, top : x.parentX(), left : x.parentY(), target : i.opt.content || null, 
                    transparent : "transparent", dontChangeColor : i.opt.dontChangeColor || false, onBefore : i.opt.onBefore || function () {},
                    onEnd : i.opt.onEnd || function () {}, onAnimation : i.opt.onAnimation || function () {}
                };
                b.B.ie6 && (u.transparent = "#123456");
                var t = function ()
                {
                    return {
                        backgroundColor : u.transparent, fontSize : 0 + "px", lineHeight : 0 + "px", borderTopWidth : 0 + "px", 
                        borderLeftWidth : 0 + "px", borderRightWidth : 0 + "px", borderBottomWidth : 0 + "px", 
                        borderTopColor : u.transparent, borderBottomColor : u.transparent, borderLeftColor : u.transparent, 
                        borderRightColor : u.transparent, background : "none", borderStyle : "solid", 
                        height : 0 + "px", width : 0 + "px"
                    }
                };
                var s = function ()
                {
                    var g = (u.height / 100) * 25;
                    var f = t();
                    f.width = u.width + "px";
                    return {
                        start : f, first : 
                        {
                            borderTopWidth : 0 + "px", borderLeftWidth : g + "px", borderRightWidth : g + "px", 
                            borderBottomWidth : 0 + "px", borderTopColor : i.opt.tocolor, borderBottomColor : i.opt.tocolor, 
                            top : (u.top + (u.height / 2)) + "px", left : (u.left - g) + "px"
                        },
                        second : 
                        {
                            borderTopWidth : 0 + "px", borderLeftWidth : 0 + "px", borderRightWidth : 0 + "px", 
                            borderBottomWidth : 0 + "px", borderTopColor : u.transparent, borderBottomColor : u.transparent, 
                            top : u.top + "px", left : u.left + "px"
                        }
                    }
                };
                var r = function ()
                {
                    var g = (u.height / 100) * 25;
                    var f = t();
                    f.height = u.height + "px";
                    return {
                        start : f, first : 
                        {
                            borderTopWidth : g + "px", borderLeftWidth : 0 + "px", borderRightWidth : 0 + "px", 
                            borderBottomWidth : g + "px", borderLeftColor : i.opt.tocolor, borderRightColor : i.opt.tocolor, 
                            top : u.top - g + "px", left : (u.left + (u.width / 2)) + "px"
                        },
                        second : 
                        {
                            borderTopWidth : 0 + "px", borderLeftWidth : 0 + "px", borderRightWidth : 0 + "px", 
                            borderBottomWidth : 0 + "px", borderLeftColor : u.transparent, borderRightColor : u.transparent, 
                            top : u.top + "px", left : u.left + "px"
                        }
                    }
                };
                z = 
                {
                    tb : function ()
                    {
                        var f = s();
                        f.start.borderTopWidth = u.height + "px";
                        f.start.borderTopColor = u.bgColor;
                        f.second.borderBottomWidth = u.height + "px";
                        f.second.borderBottomColor = u.toColor;
                        return f;
                    },
                    bt : function ()
                    {
                        var f = s();
                        f.start.borderBottomWidth = u.height + "px";
                        f.start.borderBottomColor = u.bgColor;
                        f.second.borderTopWidth = u.height + "px";
                        f.second.borderTopColor = u.toColor;
                        return f;
                    },
                    lr : function ()
                    {
                        var f = r();
                        f.start.borderLeftWidth = u.width + "px";
                        f.start.borderLeftColor = u.bgColor;
                        f.second.borderRightWidth = u.width + "px";
                        f.second.borderRightColor = u.toColor;
                        return f;
                    },
                    rl : function ()
                    {
                        var f = r();
                        f.start.borderRightWidth = u.width + "px";
                        f.start.borderRightColor = u.bgColor;
                        f.second.borderLeftWidth = u.width + "px";
                        f.second.borderLeftColor = u.toColor;
                        return f;
                    }
                };
                var A = function ()
                {
                    i.cons.item(j).setStyle("marginLeft", "-999px");
                    k();
                    B = z[u.direction]();
                    b.B.ie6 && (B.start.filter = "chroma(color=" + u.transparent + ")");
                    var f = 0;
                    i.flipscrolling = 1;
                    w.html("").setStyle(B.start);
                    w.setStyle("marginLeft", "auto");
                    w.go(B.first, i.opt.interval / 2);
                    w.go(B.second, i.opt.interval / 2, "", function ()
                    {
                        i.cons.item(j).setStyle("marginLeft", "auto");
                        w.remove();
                        i.flipscrolling = 0;
                    });
                    i.lock = 0;
                };
                if (i.opt.auto || i.lock == 1) {
                    A()
                }
                i.opt.auto && (i.runId = setTimeout(function ()
                {
                    i.next()
                },
                i.opt.interval + 500));
                return false;
            },
            tabscroll : function (g, h, f)
            {
                g.runssId && clearTimeout(g.runssId);
                g.scrlen = f;
                g.end1 = (g.y == f) ? 1 : 0;
                if (!g.end1)
                {
                    if (g.y < f) {
                        g.y += Math.ceil((f - g.y) / 5)
                    }
                    if (g.y > f) {
                        g.y -= Math.ceil((g.y - f) / 5)
                    }
                    g.tabobj.scrollLeft(g.y);
                    g.runssId = setTimeout(function ()
                    {
                        g.effects.tabscroll(g, h, f)
                    }, 10)
                }
                else {
                    return false;
                }
            }
        },
        consFix : function (f, h, g)
        {
            if (f.opt.effect == "slide")
            {
                if (f.opt.vertical == "left") {
                    b(f.cont).go({
                        left : h + "px"
                    },
                    f.fxspeed / g).delay(10)
                }
                else {
                    if (f.opt.vertical == "top") {
                        b(f.cont).go({
                            top : h + "px"
                        },
                        f.fxspeed / g).delay(10)
                    }
                }
            }
            else
            {
                if (f.opt.vertical == "left")
                {
                    if (!f.lock2) {
                        return false
                    }
                    f.lock2 = 1;
                    b(f.cont).go({
                        left : h + "px"
                    },
                    f.fxspeed / g, f.fxeffect).delay(10);
                    f.lock2 = 0
                }
                else
                {
                    if (f.opt.vertical == "top")
                    {
                        if (!f.lock2) {
                            return false
                        }
                        f.lock2 = 1;
                        b(f.cont).go({
                            top : h + "px"
                        },
                        f.fxspeed / g, f.fxeffect).delay(10);
                        f.lock2 = 0;
                    }
                }
            }
        },
        tabsFix : function (g, f)
        {
            if (g.opt.vertical == "left") {
                if (g.tabwr) {
                    g.effects.tabscroll(g, g.current, f)
                }
            }
            else {
                if (g.opt.vertical == "top") {}
            }
        },
        timeManger1 : function (h)
        {
            if (h.opt.effect == "def") {
                h.runId && clearTimeout(h.runId)
            }
            else
            {
                if (h.opt.effect == "fxslide" || h.opt.effect == "slide" && !h.end)
                {
                    var j = h.to;
                    var g = h.scrlen;
                    var i = parseInt(h.cont.style[h.Tol] ? h.cont.style[h.Tol] : "0px");
                    var f = (i == j) ? 1 : 0;
                    if (!f)
                    {
                        if (h.opt.effect == "slide") {
                            h.consFix(h, j, 3);
                            h.tabsFix(h, g)
                        }
                        else {
                            if (h.opt.effect == "fxslide") {
                                h.consFix(h, j, 10);
                                h.tabsFix(h, g)
                            }
                        }
                        h.opt.mousecurrent = this.current;
                        h.runId && clearTimeout(h.runId)
                    }
                }
                else
                {
                    if (h.opt.effect == "fade") {
                        h.opt.mousecurrent = this.current;
                        h.runId && clearTimeout(h.runId)
                    }
                    else
                    {
                        if (h.opt.effect == "flip") {
                            h.lock = 1;
                            h.runId && clearTimeout(h.runId);
                            return false
                        }
                        else {
                            h.opt.mousecurrent = this.current;
                            h.runId && clearTimeout(h.runId)
                        }
                    }
                }
            }
        },
        timeManger2 : function (f)
        {
            if (f.opt.effect == "fade")
            {
                f.runId && clearTimeout(f.runId);
                f.opt.auto && (f.runId = setTimeout(function ()
                {
                    f.next()
                },
                f.opt.interval))
            }
            else
            {
                if (f.opt.effect == "flip")
                {
                    f.runId && clearTimeout(f.runId);
                    f.opt.auto && (f.runId = setTimeout(function ()
                    {
                        f.next()
                    },
                    f.opt.interval))
                }
                else
                {
                    f.runId && clearTimeout(f.runId);
                    f.opt.auto && (f.runId = setTimeout(function ()
                    {
                        f.next()
                    },
                    f.opt.interval))
                }
            }
        },
        tabMoveFix : function (f, g)
        {
            if (this.opt.effect == "slide" || this.opt.effect == "fxslide" || this.opt.effect == "scroll") {
                f.to = "-" + g * this.ml;
                this.end = 0
            }
            else
            {
                if (this.opt.effect == "fade") {
                    if (!this.opt.pageBt) {
                        f.tabs.opacity(0.5);
                        f.tabs.item(g).opacity(1)
                    }
                }
            }
            b.A.each(this.levels, function (h)
            {
                if (g == h)
                {
                    f.tabs.item(h).addClass(f.opt.curCn);
                    if (f.texts) {
                        f.texts.item(h).addClass(f.opt.curCn)
                    }
                }
                else
                {
                    f.tabs.item(h).removeClass(f.opt.curCn);
                    if (f.texts) {
                        f.texts.item(h).removeClass(f.opt.curCn)
                    }
                }
            });
            f.current = g;
            f.effectFn(f, g, f.to)
        },
        tabnext : function (g)
        {
            g.runId && clearTimeout(g.runId);
            var h = g.current;
            if (g.tabwr)
            {
                var f = g.tabnum * g.tabitemlen * g.sn;
                if (g.sn < g.tn)
                {
                    if (g.opt.effect == "def") {
                        b(g.opt.id).find(g.tabwr).item(0).scrollLeft(f)
                    }
                    else
                    {
                        if (g.opt.effect == "slide" || g.opt.effect == "fxslide" || g.opt.effect == "fade") {
                            g.effects.tabscroll(g, h, f)
                        }
                        else {
                            b(g.opt.id).find(g.tabwr).item(0).scrollLeft(f)
                        }
                    }
                    g.tabMoveFix(g, g.shownum);
                    g.sn++;
                    g.tn--
                }
                else
                {
                    if (g.opt.effect == "def") {
                        b(g.opt.id).find(g.tabwr).item(0).scrollLeft(0)
                    }
                    else
                    {
                        if (g.opt.effect == "slide" || g.opt.effect == "fxslide" || g.opt.effect == "fade") {
                            g.effects.tabscroll(g, h, 0)
                        }
                        else {
                            b(g.opt.id).find(g.tabwr).item(0).scrollLeft(0)
                        }
                    }
                    g.tabMoveFix(g, 0);
                    g.sn = 1;
                    g.tn = g.temptn;
                }
            }
        },
        tabprev : function (g)
        {
            g.runId && clearTimeout(g.runId);
            var h = g.current;
            if (g.tabwr)
            {
                var f = g.tabitemlen * (g.levels / 2);
                f = f - g.tabnum * g.tabitemlen * (g.sn - 1);
                if (g.sn < g.tn)
                {
                    if (g.opt.effect == "def") {
                        b(g.opt.id).find(g.tabwr).item(0).scrollLeft(f)
                    }
                    else
                    {
                        if (g.opt.effect == "slide" || g.opt.effect == "fxslide" || g.opt.effect == "fade") {
                            g.effects.tabscroll(g, h, f)
                        }
                        else {
                            b(g.opt.id).find(g.tabwr).item(0).scrollLeft(f)
                        }
                    }
                    g.tabMoveFix(g, g.shownum);
                    g.sn++;
                    g.tn--
                }
                else
                {
                    if (g.opt.effect == "def") {
                        b(g.opt.id).find(g.tabwr).item(0).scrollLeft(0)
                    }
                    else
                    {
                        if (g.opt.effect == "slide" || g.opt.effect == "fxslide" || g.opt.effect == "fade") {
                            g.effects.tabscroll(g, h, 0)
                        }
                        else {
                            b(g.opt.id).find(g.tabwr).item(0).scrollLeft(0)
                        }
                    }
                    g.tabMoveFix(g, 0);
                    g.sn = 1;
                    g.tn = g.temptn;
                }
            }
        },
        fixMoveTo : function (h)
        {
            var g = this, h, f;
            g.runId && clearTimeout(g.runId);
            (h > this.levels - 1) && (h = 0);
            (h < 0) && (h = this.levels - 1);
            if (this.opt.effect == "slide" || this.opt.effect == "fxslide" || this.opt.effect == "scroll") {
                g.to = "-" + h * this.ml;
                this.end = 0
            }
            else
            {
                if (this.opt.effect == "fade")
                {
                    if (!g.opt.pageBt) {
                        g.tabs.opacity(0.5);
                        g.tabs.item(h).opacity(1)
                    }
                    g.opt.auto && (g.runId = setTimeout(function ()
                    {
                        g.next()
                    },
                    g.opt.interval))
                }
            }
            b.A.each(this.levels, function (j)
            {
                if (h == j)
                {
                    g.tabs.item(j).addClass(g.opt.curCn);
                    if (g.texts) {
                        g.texts.item(j).addClass(g.opt.curCn)
                    }
                }
                else
                {
                    g.tabs.item(j).removeClass(g.opt.curCn);
                    if (g.texts) {
                        g.texts.item(j).removeClass(g.opt.curCn)
                    }
                }
            });
            this.current = h;
            if (g.opt.numId && g.opt.totalId) {
                b(g.opt.numId).html(this.current + 1)
            }
            this.effectFn(this, h, g.to)
        },
        moveTo : function (h)
        {
            var g = this, h, f;
            g.runId && clearTimeout(g.runId);
            (h > this.levels - 1) && (h = 0);
            (h < 0) && (h = this.levels - 1);
            if (g.tabwr)
            {
                if (h == g.shownum) {
                    if (g.sn == g.tn) {
                        g.sn = 1;
                        g.tn = g.temptn;
                        g.y = 0;
                        g.tabnext(g)
                    }
                    else {
                        g.tabnext(g)
                    }
                }
                else
                {
                    if (h == 0)
                    {
                        if (g.opt.effect == "def") {
                            b(g.opt.id).find(g.tabwr).item(0).scrollLeft(0)
                        }
                        else
                        {
                            if (g.opt.effect == "slide" || g.opt.effect == "fxslide" || g.opt.effect == "fade") {
                                g.effects.tabscroll(g, h, 0)
                            }
                            else {
                                b(g.opt.id).find(g.tabwr).item(0).scrollLeft(0)
                            }
                        }
                    }
                }
            }
            if (this.opt.effect == "slide" || this.opt.effect == "fxslide" || this.opt.effect == "scroll") {
                g.to = "-" + h * this.ml;
                this.end = 0
            }
            else
            {
                if (this.opt.effect == "fade")
                {
                    if (!g.opt.pageBt) {
                        g.tabs.opacity(0.5);
                        g.tabs.item(h).opacity(1)
                    }
                    g.opt.auto && (g.runId = setTimeout(function ()
                    {
                        g.next()
                    },
                    g.opt.interval))
                }
            }
            b.A.each(this.levels, function (j)
            {
                if (h == j)
                {
                    g.tabs.item(j).addClass(g.opt.curCn);
                    if (g.texts) {
                        g.texts.item(j).addClass(g.opt.curCn)
                    }
                }
                else
                {
                    g.tabs.item(j).removeClass(g.opt.curCn);
                    if (g.texts) {
                        g.texts.item(j).removeClass(g.opt.curCn)
                    }
                }
            });
            this.current = h;
            if (g.opt.numId && g.opt.totalId) {
                b(g.opt.numId).html(this.current + 1)
            }
            this.effectFn(this, h, g.to)
        },
        scrTo : function (g)
        {
            var f = this;
            this.runId && clearTimeout(this.runId);
            var f = this, g;
            f.to = "-" + g * this.ml;
            this.current = g;
            this.effectFn(this, g, f.to)
        },
        scrprev : function ()
        {
            this.scrTo(--this.current)
        },
        scrnext : function ()
        {
            this.scrTo(++this.current)
        },
        prev : function ()
        {
            this.th2 = 1;
            this.moveTo(--this.current)
        },
        next : function ()
        {
            this.th2 = 1;
            this.moveTo(++this.current)
        },
        tcTime : function ()
        {
            this.simuId && clearTimeout(this.simuId);
            this.th2 = 1;
            this.simulate(this)
        },
        simulate : function (f)
        {
            f.simuId = setTimeout(function ()
            {
                f.tcTime()
            }, 1000)
        }
    });
    b.tabs = function (f)
    {
        return new d(f);
    };
    b.P.childWrap = function (g, f)
    {
        return Koala.each(function (j)
        {
            if (f) {
                j = j.child(f)
            }
            var i = j.html();
            j.empty();
            j.html(g);
            var h = j.node;
            while (h.firstChild) {
                h = h.firstChild
            }
            h.innerHTML = i;
        }, this)
    };
    var a = KClass.create(
    {
        init : function (f)
        {
            this.opt = 
            {
                id : "scroll", Speed : 10, Space : 10, eType : "click", effect : "scroll", scWr : ".scwr", 
                scrElem : "li", scrNum : 1, showNum : 4, auto : 1, vertical : "left", loop : false, interval : 2000
            };
            b.extend(this.opt, f);
            var h = this;
            var g = this.opt;
            this.Speed = g.Speed;
            this.Space =- g.Space;
            this.loop = g.loop;
            this.showNum = g.showNum;
            this.vertical = g.vertical;
            this.interval = g.interval;
            this.scrElem = g.scrElem;
            this.prevMouseImg = g.prevMouseClass;
            this.prevStopImg = g.prevStopClass;
            this.nextMouseImg = g.nextMouseClass;
            this.nextStopImg = g.nextStopClass;
            h.scWr = b(g.id).find(h.opt.scWr).item(0);
            if (g.vertical == "left")
            {
                h.scWr.childWrap("<div class='cols' style='position:absolute;width:9999px'><div class='count' style='overflow:hidden;float:left'></div></div>")
            }
            else
            {
                h.scWr.childWrap("<div class='cols' style='position:absolute;height:9999px'><div class='count' style='overflow:hidden;float:left'></div></div>")
            }
            h.fill = 0;
            h.Comp = 0;
            h.Stop = 0;
            h.test = 0;
            h.finish = 0;
            h.fMoveX = 0;
            h.scrObj = b(g.id).find(".cols").item(0);
            h.MoveLock = false;
            h.MoveTimeObj = null;
            h.AutoPlayObj = null;
            h.scrEvent1 = null;
            h.btMouse1 = null;
            h.ptMouse1 = null;
            h.scrWrap = h.scrObj.find(".count").item(0);
            h.Wrapchild = h.scrWrap.child(0);
            h.scrlen = h.scrWrap.find(h.scrElem).len;
            h.scrWrap2 = h.scrWrap.clone(true);
            h.scrWrap.parent(1).append(h.scrWrap2);
            if (b.B.ie)
            {
                if (h.vertical == "left")
                {
                    if (parseInt(h.Wrapchild.css("margin-right")) && !parseInt(h.Wrapchild.css("margin-left")))
                    {
                        this.elemLength = h.Wrapchild.node.offsetWidth + parseInt(h.Wrapchild.css("margin-right"))
                    }
                    if (parseInt(h.Wrapchild.css("margin-left")) && !parseInt(h.Wrapchild.css("margin-right")))
                    {
                        this.elemLength = h.Wrapchild.node.offsetWidth + parseInt(h.Wrapchild.css("margin-left"))
                    }
                    if (parseInt(h.Wrapchild.css("margin-right")) && parseInt(h.Wrapchild.css("margin-left")))
                    {
                        this.elemLength = h.Wrapchild.node.offsetWidth + parseInt(h.Wrapchild.css("margin-left")) + parseInt(h.Wrapchild.css("margin-right"))
                    }
                    if (!parseInt(h.Wrapchild.css("margin-right")) && !parseInt(h.Wrapchild.css("margin-left"))) {
                        this.elemLength = h.Wrapchild.node.offsetWidth;
                    }
                }
                else
                {
                    if (parseInt(h.Wrapchild.css("margin-bottom")) && !parseInt(h.Wrapchild.css("margin-top")))
                    {
                        this.elemLength = h.Wrapchild.node.offsetHeight + parseInt(h.Wrapchild.css("margin-bottom"))
                    }
                    if (parseInt(h.Wrapchild.css("margin-top")) && !parseInt(h.Wrapchild.css("margin-bottom")))
                    {
                        this.elemLength = h.Wrapchild.node.offsetHeight + parseInt(h.Wrapchild.css("margin-top"))
                    }
                    if (parseInt(h.Wrapchild.css("margin-bottom")) && parseInt(h.Wrapchild.css("margin-top")))
                    {
                        this.elemLength = h.Wrapchild.node.offsetHeight + parseInt(h.Wrapchild.css("margin-top")) + parseInt(h.Wrapchild.css("margin-bottom"))
                    }
                    if (!parseInt(h.Wrapchild.css("margin-bottom")) && !parseInt(h.Wrapchild.css("margin-top"))) {
                        this.elemLength = h.Wrapchild.node.offsetHeight;
                    }
                }
            }
            else
            {
                h.vertical == "left" ? this.elemLength = h.Wrapchild.node.offsetWidth + parseInt(h.Wrapchild.css("margin-right")) + parseInt(h.Wrapchild.css("margin-left")) : this.elemLength = h.Wrapchild.node.offsetHeight + parseInt(h.Wrapchild.css("margin-bottom")) + parseInt(h.Wrapchild.css("margin-top"))
            }
            this.PageWidth = this.elemLength * g.scrNum;
            this.finalMovePos = this.elemLength * (h.scrlen - h.showNum);
            h.scrWrapWidth = h.scrWrap.width();
            h.scrWrapHeight = h.scrWrap.height();
            h.scrObj.hover(function ()
            {
                clearInterval(h.AutoPlayObj)
            },
            function ()
            {
                g.auto && h.AutoPlay(h)
            });
            if (g.bns && g.bns.length == 2)
            {
                h.btleft = b(g.id).find(g.bns[0]).item(0);
                h.btright = b(g.id).find(g.bns[1]).item(0);
                h.btnLeftImg = h.btleft.classNames();
                h.btnRightImg = h.btright.classNames();
                h.scrEvent1 = h.btleft.bind("_mousedown", function ()
                {
                    h.ISL_GoUp(h)
                });
                h.btMouse1 = h.btleft.bind("_mouseover", function ()
                {
                    this.toggleClass(h.prevMouseImg);
                    clearInterval(h.AutoPlayObj)
                });
                h.mouseup1 = h.btleft.bind("_mouseup", function ()
                {
                    h.ISL_StopUp(h);
                    clearInterval(h.AutoPlayObj)
                });
                h.ptMouse1 = h.btleft.bind("_mouseout", function ()
                {
                    this.toggleClass(h.prevMouseImg);
                    g.auto && h.AutoPlay(h)
                });
                h.btMouse2 = h.btright.bind("_mouseover", function ()
                {
                    this.toggleClass(h.nextMouseImg);
                    clearInterval(h.AutoPlayObj)
                });
                h.scrEvent2 = h.btright.bind("_mousedown", function ()
                {
                    h.ISL_GoDown(h);
                    h.test = 1;
                });
                h.mouseup2 = h.btright.bind("_mouseup", function ()
                {
                    h.ISL_StopDown(h);
                    clearInterval(h.AutoPlayObj)
                });
                h.ptMouse2 = h.btright.bind("_mouseout", function ()
                {
                    this.removeClass(h.nextMouseImg);
                    g.auto && h.AutoPlay(h)
                });
                !h.loop && h.btleft.toggleClass(h.prevStopImg)
            }
            g.auto && h.AutoPlay(h)
        },
        leftEvent : function (f)
        {
            f.scrEvent1 = f.btleft.bind("_mousedown", function ()
            {
                f.ISL_GoUp(f)
            });
            f.btMouse1 = f.btleft.bind("_mouseover", function ()
            {
                this.toggleClass(f.prevMouseImg);
                clearInterval(f.AutoPlayObj)
            });
            f.mouseup1 = f.btleft.bind("_mouseup", function ()
            {
                f.ISL_StopUp(f);
                clearInterval(f.AutoPlayObj)
            });
            f.ptMouse1 = f.btleft.bind("_mouseout", function ()
            {
                this.toggleClass(f.prevMouseImg);
                opt.auto && f.AutoPlay(f)
            })
        },
        rightEvent : function (f)
        {
            f.ISL_GoDown(f);
            f.test = 1;
            this.toggleClass(f.nextMouseImg);
            clearInterval(f.AutoPlayObj);
            f.mouseup2 = f.btright.bind("touchend", function ()
            {
                f.ISL_StopDown(f);
                clearInterval(f.AutoPlayObj)
            });
            this.removeClass(f.nextMouseImg);
            opt.auto && f.AutoPlay(f)
        },
        linkTo : function (f)
        {
            if (this.opt.ipNewPage) {
                window.open(f.parent().node.href, "_blank")
            }
            else {
                window.location.href = f.parent().node.href;
            }
        },
        tochStart : function (g, f)
        {
            g.stopImmediatePropagation();
            g.preventDefault();
            this.runId && clearTimeout(this.runId);
            if (g.targetTouches.length > 1) {
                this.oneFinger = false;
                return
            }
            else {
                this.oneFinger = true
            }
            this.fMoveX = g.changedTouches[0].pageX;
        },
        tochEnd : function (g, f)
        {
            if (!this.oneFinger) {
                return
            }
            if (this.fMoveX < g.changedTouches[0].pageX) {
                this.leftEvent(this)
            }
            else
            {
                if (this.fMoveX > g.changedTouches[0].pageX) {
                    this.rightEvent(this)
                }
                else {
                    return this.linkTo(f);
                }
            }
        },
        disbtleft : function (f)
        {
            f.btleft.unbind("mouseover", f.btMouse1);
            f.btleft.unbind("mouseout", f.ptMouse1);
            f.btleft.unbind("mouseup", f.mouseup1);
            f.btleft.unbind("mousedown", f.scrEvent1);
            f.btleft.removeClass(f.prevMouseImg).addClass(f.prevStopImg)
        },
        disbtright : function (f)
        {
            f.btright.unbind("mousedown", f.scrEvent2);
            f.btright.unbind("mouseup", f.mouseup2);
            f.btright.unbind("mouseover", f.btMouse2);
            f.btright.unbind("mouseout", f.ptMouse2);
            f.btright.removeClass(f.nextMouseImg).addClass(f.nextStopImg)
        },
        enabbtleft : function (f)
        {
            if (f.scrEvent1 || f.btMouse1 || f.ptMouse1) {
                f.disbtleft(f);
                f.btleft.removeClass(f.prevStopImg)
            }
            f.scrEvent1 = f.btleft.bind("_mousedown", function ()
            {
                f.ISL_GoUp(f)
            });
            f.btMouse1 = f.btleft.bind("_mouseover", function ()
            {
                this.toggleClass(f.prevMouseImg);
                clearInterval(f.AutoPlayObj)
            });
            f.mouseup1 = f.btleft.bind("_mouseup", function ()
            {
                f.ISL_StopUp(f);
                clearInterval(f.AutoPlayObj)
            });
            f.ptMouse1 = f.btleft.bind("_mouseout", function ()
            {
                this.removeClass(f.prevMouseImg);
                f.opt.auto && f.AutoPlay(f)
            });
            f.MoveLock = false;
        },
        enabbtright : function (f)
        {
            if (f.scrEvent2 || f.btMouse2 || f.ptMouse2) {
                f.disbtright(f);
                f.btright.removeClass(f.nextStopImg)
            }
            f.btMouse2 = f.btright.bind("_mouseover", function ()
            {
                this.toggleClass(f.nextMouseImg);
                clearInterval(f.AutoPlayObj)
            });
            f.scrEvent2 = f.btright.bind("_mousedown", function ()
            {
                f.ISL_GoDown(f)
            });
            f.mouseup2 = f.btright.bind("_mouseup", function ()
            {
                f.ISL_StopDown(f)
            });
            f.ptMouse2 = f.btright.bind("_mouseout", function ()
            {
                this.removeClass(f.nextMouseImg);
                f.opt.auto && f.AutoPlay(f)
            });
            f.MoveLock = false;
        },
        AutoPlay : function (f)
        {
            clearInterval(f.AutoPlayObj);
            if (f.finish && !f.loop) {
                return
            }
            f.AutoPlayObj = setInterval(function ()
            {
                f.ISL_GoDown(f);
                f.ISL_StopDown(f)
            },
            f.interval)
        },
        ISL_GoDown : function (f)
        {
            !f.loop && f.enabbtleft(f);
            clearInterval(f.MoveTimeObj);
            if (f.MoveLock) {
                return
            }
            clearInterval(f.AutoPlayObj);
            f.ISL_ScrDown(f);
            f.MoveTimeObj = setInterval(function ()
            {
                f.ISL_ScrDown(f)
            },
            f.Speed);
            f.MoveLock = true;
        },
        ISL_GoUp : function (f)
        {
            !f.loop && f.enabbtright(f);
            clearInterval(f.MoveTimeObj);
            if (f.MoveLock) {
                return
            }
            f.AutoPlayObj && clearInterval(f.AutoPlayObj);
            f.MoveTimeObj = setInterval(function ()
            {
                f.ISL_ScrUp(f)
            },
            f.Speed);
            f.MoveLock = true;
            f.finish = 0;
        },
        ISL_StopUp : function (f)
        {
            clearInterval(f.MoveTimeObj);
            if (f.vertical == "left")
            {
                if (f.scrObj.Left() % f.PageWidth - f.fill != 0) {
                    f.Comp = f.fill - (f.scrObj.Left() % f.PageWidth);
                    f.CompScr(f)
                }
                else {
                    f.MoveLock = false;
                }
            }
            else
            {
                if (f.scrObj.Top() % f.PageWidth - f.fill != 0) {
                    f.Comp = f.fill - (f.scrObj.Top() % f.PageWidth);
                    f.CompScr(f)
                }
                else {
                    f.MoveLock = false;
                }
            }
            f.opt.auto && f.AutoPlay(f)
        },
        ISL_ScrUp : function (f)
        {
            if (f.vertical == "left")
            {
                if (-f.scrObj.Left() <= 0) {
                    if (!f.loop) {
                        return
                    }
                    f.scrObj.Left(f.scrObj.Left() - f.scrWrapWidth)
                }
                if (-f.scrObj.Left() <= f.PageWidth)
                {
                    !f.loop && f.disbtleft(f);
                    !f.loop && f.opt.auto && setTimeout(function ()
                    {
                        f.AutoPlay(f)
                    },
                    f.interval)
                }
                f.scrObj.Left(f.scrObj.Left() - f.Space)
            }
            else
            {
                if (-f.scrObj.Top() <= 0) {
                    if (!f.loop) {
                        return
                    }
                    f.scrObj.Top(f.scrObj.Top() - f.scrWrapHeight)
                }
                if (-f.scrObj.Top() <= f.PageWidth)
                {
                    !f.loop && f.disbtleft(f);
                    !f.loop && f.opt.auto && setTimeout(function ()
                    {
                        f.AutoPlay(f)
                    },
                    f.interval)
                }
                f.scrObj.Top(f.scrObj.Top() - f.Space)
            }
        },
        ISL_ScrDown : function (f)
        {
            if (f.vertical == "left")
            {
                if (!f.loop &&- f.scrObj.Left() > f.finalMovePos) {
                    f.disbtright(f);
                    f.Stop = 1;
                    return
                }
                if (f.scrObj.Left() <=- f.scrWrapWidth) {
                    f.scrObj.Left(f.scrObj.Left() + f.scrWrapWidth)
                }
                f.scrObj.Left(f.scrObj.Left() + f.Space);
                if (!f.loop &&- f.scrObj.Left() > f.finalMovePos - f.PageWidth) {
                    f.disbtright(f);
                    f.Stop = 1;
                    f.finish = 1;
                }
            }
            else
            {
                if (!f.loop &&- f.scrObj.Top() > f.finalMovePos) {
                    f.disbtright(f);
                    f.Stop = 1;
                    return
                }
                if (f.scrObj.Top() <=- f.scrWrapHeight) {
                    f.scrObj.Top(f.scrObj.Top() + f.scrWrapHeight)
                }
                f.scrObj.Top(f.scrObj.Top() + f.Space);
                if (!f.loop &&- f.scrObj.Top() > f.finalMovePos - f.PageWidth) {
                    f.disbtright(f);
                    f.Stop = 1;
                    f.finish = 1;
                }
            }
        },
        ISL_StopDown : function (f)
        {
            clearInterval(f.MoveTimeObj);
            if (f.vertical == "left")
            {
                if (!f.loop && f.Stop &&- f.scrObj.Left() > f.finalMovePos - f.PageWidth)
                {
                    setTimeout(function ()
                    {
                        clearInterval(f.AutoPlayObj)
                    },
                    f.interval);
                    f.MoveLock = false;
                    f.Stop = 0;
                    f.finish = 1
                }
                if (f.scrObj.Left() % f.PageWidth - f.fill != 0) {
                    f.Comp = 0 - f.PageWidth - f.scrObj.Left() % f.PageWidth + f.fill;
                    f.CompScr(f)
                }
                else {
                    f.MoveLock = false;
                }
            }
            else
            {
                if (!f.loop && f.Stop &&- f.scrObj.Top() > f.finalMovePos - f.PageWidth)
                {
                    setTimeout(function ()
                    {
                        clearInterval(f.AutoPlayObj)
                    },
                    f.interval);
                    f.MoveLock = false;
                    f.Stop = 0;
                    f.finish = 1
                }
                if (f.scrObj.Top() % f.PageWidth - f.fill != 0) {
                    f.Comp = 0 - f.PageWidth - f.scrObj.Top() % f.PageWidth + f.fill;
                    f.CompScr(f)
                }
                else {
                    f.MoveLock = false;
                }
            }
            if (f.test) {
                f.test = 0;
                return
            }
            else {
                f.opt.auto && f.AutoPlay(f)
            }
        },
        CompScr : function (g)
        {
            var f;
            if (g.Comp == 0) {
                g.MoveLock = false;
                return
            }
            if (g.Comp > 0)
            {
                if (g.Comp > -g.Space) {
                    g.Comp += g.Space;
                    f = g.Space
                }
                else {
                    f =- g.Comp;
                    g.Comp = 0
                }
                if (g.vertical == "left") {
                    g.scrObj.Left(g.scrObj.Left() - f)
                }
                else {
                    g.scrObj.Top(g.scrObj.Top() - f)
                }
                setTimeout(function ()
                {
                    g.CompScr(g)
                },
                g.Speed)
            }
            else
            {
                if (g.Comp < g.Space) {
                    g.Comp -= g.Space;
                    f = g.Space
                }
                else {
                    f = g.Comp;
                    g.Comp = 0
                }
                if (g.vertical == "left") {
                    g.scrObj.Left(g.scrObj.Left() + f)
                }
                else {
                    g.scrObj.Top(g.scrObj.Top() + f)
                }
                setTimeout(function ()
                {
                    g.CompScr(g)
                },
                g.Speed)
            }
        }
    });
    b.scroll = function (f)
    {
        return new a(f);
    };
    function e(j)
    {
        var h = document.createElement(/<\w+/.exec(j)[0].substr(1)), k = j.substr(0, j.indexOf(">") + 1).match(/\w+=(['"])[^>]*?\1/g);
        if (k && k.length > 0)
        {
            var g = 0;
            while (k[g])
            {
                var f = k[g].split("=");
                if (f[1] = f[1].replace(/['"]/g, "")) {
                    h.setAttribute(f[0], f[1])
                }
                g++
            }
        }
        h.innerHTML = j.substring(j.indexOf(">") + 1, j.lastIndexOf("<")).replace(/^\s+|\s+$/g, "");
        return h
    }
    function c(f, g)
    {
        if (f.filters) {
            f.style.filter = "alpha(opacity=" + g + ")"
        }
        else {
            f.style.opacity = g / 100;
        }
    }
})(Koala);
