(function ()
{
    var e = window.K;
    this._win = window;
    this._doc = document;
    this.slice = Array.prototype.slice;
    this._head = document.getElementsByTagName("head")[0];
    var g = this.K = this._K = this.Koala = function (l)
    {
        if (g.C.isKdom(l)) {
            return l
        }
        if (g.C.isfun(l)) {
            g.ready(l);
            return
        }
        var m = g.C.node(arguments.length > 0 ? l : _win);
        if (m) {
            var k = new f(m);
            k.toString = function ()
            {
                return "Kdom";
            };
            return k
        }
        return new h(l);
    };
    (function (k)
    {
        var m = this.KK = function ()
        {
            var n = l(arguments).join(", ");
            return g.Selector.select(n, document);
        };
        var l = this.KA = function (p)
        {
            if (!p) {
                return []
            }
            if ("toArray"in Object(p)) {
                return p.toArray()
            }
            var o = p.length || 0, n = new Array(o);
            while (o--) {
                n[o] = p[o]
            }
            return n;
        };
        m.doms = function (p, q, o)
        {
            if (arguments.length > 1) {
                a = arguments
            }
            else
            {
                if (g.C.isKdoms(p)) {
                    return p
                }
                else
                {
                    if (g.C.isarr(p) || g.C.isDoms(p)) {
                        a = p
                    }
                    else
                    {
                        if (g.C.isstr(p))
                        {
                            a = /^n:(\w+)$/.test(p) ? _doc.getElementsByName(RegExp.$1) : _doc.getElementsByTagName(p)
                        }
                    }
                }
            }
            if (p && p.length > 0) {
                var n = new b(a, q);
                if (n.len == 1) {
                    return g(n.item(0))
                }
                else {
                    return n;
                }
            }
            return new h(o);
        };
        g.id = function (n)
        {
            return document.getElementById(n);
        };
        g.noConflict = function ()
        {
            if (g.id) {
                window.K = e
            }
            return _K;
        };
        (function ()
        {
            var x = /((?:\((?:\([^()]+\)|[^()]+)+\)|\[(?:\[[^\[\]]*\]|['"][^'"]*['"]|[^\[\]'"]+)+\]|\\.|[^ >+~,(\[\\]+)+|[>+~])(\s*,\s*)?((?:.|\r|\n)*)/g, 
            y = 0, B = Object.prototype.toString, s = false, r = true, z = /\\/g, G = /\W/;
            [0, 0].sort(function ()
            {
                r = false;
                return 0;
            });
            var p = function (M, H, P, Q)
            {
                P = P || [];
                H = H || document;
                var S = H;
                if (H.nodeType !== 1 && H.nodeType !== 9){
                    return []
                }
                if (!M || typeof M !== "string") {
                    return P
                }
                var J, U, X, I, T, W, V, O, L = true, K = p.isXML(H), N = [], R = M;
                do {
                    x.exec("");
                    J = x.exec(R);
                    if (J) {
                        R = J[3];
                        N.push(J[1]);
                        if (J[2]) {
                            I = J[3];
                            break
                        }
                    }
                }
                while (J);
                if (N.length > 1 && t.exec(M))
                {
                    if (N.length === 2 && u.relative[N[0]]) {
                        U = C(N[0] + N[1], H)
                    }
                    else
                    {
                        U = u.relative[N[0]] ? [H] : p(N.shift(), H);
                        while (N.length) {
                            M = N.shift();
                            if (u.relative[M]) {
                                M += N.shift()
                            }
                            U = C(M, U);
                        }
                    }
                }
                else
                {
                    if (!Q && N.length > 1 && H.nodeType === 9 && !K && u.match.ID.test(N[0]) && !u.match.ID.test(N[N.length - 1])) {
                        T = p.find(N.shift(), H, K);
                        H = T.expr ? p.filter(T.expr, T.set)[0] : T.set[0]
                    }
                    if (H)
                    {
                        T = Q ? {
                            expr : N.pop(), set : v(Q)
                        }
                         : p.find(N.pop(), N.length === 1 && (N[0] === "~" || N[0] === "+") && H.parentNode ? H.parentNode : H, 
                        K);
                        U = T.expr ? p.filter(T.expr, T.set) : T.set;
                        if (N.length > 0) {
                            X = v(U)
                        }
                        else {
                            L = false
                        }
                        while (N.length)
                        {
                            W = N.pop();
                            V = W;
                            if (!u.relative[W]) {
                                W = ""
                            }
                            else {
                                V = N.pop()
                            }
                            if (V == null) {
                                V = H
                            }
                            u.relative[W](X, V, K);
                        }
                    }
                    else {
                        X = N = [];
                    }
                }
                if (!X) {
                    X = U
                }
                if (!X) {
                    p.error(W || M)
                }
                if (B.call(X) === "[object Array]")
                {
                    if (!L) {
                        P.push.apply(P, X)
                    }
                    else
                    {
                        if (H && H.nodeType === 1)
                        {
                            for (O = 0; X[O] != null; O++) {
                                if (X[O] && (X[O] === true || X[O].nodeType === 1 && p.contains(H, X[O]))) {
                                    P.push(U[O])
                                }
                            }
                        }
                        else {
                            for (O = 0; X[O] != null; O++) {
                                if (X[O] && X[O].nodeType === 1) {
                                    P.push(U[O])
                                }
                            }
                        }
                    }
                }
                else {
                    v(X, P)
                }
                if (I) {
                    p(I, S, P, Q);
                    p.uniqueSort(P)
                }
                return P;
            };
            p.uniqueSort = function (I)
            {
                if (A)
                {
                    s = r;
                    I.sort(A);
                    if (s) {
                        for (var H = 1; H < I.length; H++) {
                            if (I[H] === I[H - 1]) {
                                I.splice(H--, 1)
                            }
                        }
                    }
                }
                return I;
            };
            p.matches = function (H, I)
            {
                return p(H, null, null, I);
            };
            p.matchesSelector = function (H, I)
            {
                return p(I, null, null, [H]).length > 0;
            };
            p.find = function (O, H, P)
            {
                var N;
                if (!O) {
                    return []
                }
                for (var K = 0, J = u.order.length; K < J; K++)
                {
                    var L, M = u.order[K];
                    if ((L = u.leftMatch[M].exec(O)))
                    {
                        var I = L[1];
                        L.splice(1, 1);
                        if (I.substr(I.length - 1) !== "\\")
                        {
                            L[1] = (L[1] || "").replace(z, "");
                            N = u.find[M](L, H, P);
                            if (N != null) {
                                O = O.replace(u.match[M], "");
                                break
                            }
                        }
                    }
                }
                if (!N) {
                    N = typeof H.getElementsByTagName !== "undefined" ? H.getElementsByTagName("*") : []
                }
                return {
                    set : N, expr : O
                }
            };
            p.filter = function (S, R, V, L)
            {
                var N, H, J = S, X = [], P = R, O = R && R[0] && p.isXML(R[0]);
                while (S && R.length)
                {
                    for (var Q in u.filter)
                    {
                        if ((N = u.leftMatch[Q].exec(S)) != null && N[2])
                        {
                            var W, U, I = u.filter[Q], K = N[1];
                            H = false;
                            N.splice(1, 1);
                            if (K.substr(K.length - 1) === "\\") {
                                continue
                            }
                            if (P === X) {
                                X = []
                            }
                            if (u.preFilter[Q]) {
                                N = u.preFilter[Q](N, P, V, X, L, O);
                                if (!N) {
                                    H = W = true
                                }
                                else {
                                    if (N === true) {
                                        continue
                                    }
                                }
                            }
                            if (N)
                            {
                                for (var M = 0; (U = P[M]) != null; M++)
                                {
                                    if (U)
                                    {
                                        W = I(U, N, M, P);
                                        var T = L^!!W;
                                        if (V && W != null) {
                                            if (T) {
                                                H = true
                                            }
                                            else {
                                                P[M] = false;
                                            }
                                        }
                                        else {
                                            if (T) {
                                                X.push(U);
                                                H = true;
                                            }
                                        }
                                    }
                                }
                            }
                            if (W !== undefined) {
                                if (!V) {
                                    P = X
                                }
                                S = S.replace(u.match[Q], "");
                                if (!H) {
                                    return []
                                }
                                break
                            }
                        }
                    }
                    if (S === J) {
                        if (H == null) {
                            p.error(S)
                        }
                        else {
                            break
                        }
                    }
                    J = S
                }
                return P;
            };
            p.error = function (H)
            {
                throw "Syntax error, unrecognized expression: " + H
            };
            var u = p.selectors = 
            {
                order : ["ID", "NAME", "TAG"], match : 
                {
                    ID : /#((?:[\w\u00c0-\uFFFF\-]|\\.)+)/, CLASS : /\.((?:[\w\u00c0-\uFFFF\-]|\\.)+)/, 
                    NAME : /\[name=['"]*((?:[\w\u00c0-\uFFFF\-]|\\.)+)['"]*\]/, ATTR : /\[\s*((?:[\w\u00c0-\uFFFF\-]|\\.)+)\s*(?:(\S?=)\s*(?:(['"])(.*?)\3|(#?(?:[\w\u00c0-\uFFFF\-]|\\.)*)|)|)\s*\]/, 
                    TAG : /^((?:[\w\u00c0-\uFFFF\*\-]|\\.)+)/, CHILD : /:(only|nth|last|first)-child(?:\(\s*(even|odd|(?:[+\-]?\d+|(?:[+\-]?\d*)?n\s*(?:[+\-]\s*\d+)?))\s*\))?/, 
                    POS : /:(nth|eq|gt|lt|first|last|even|odd)(?:\((\d*)\))?(?=[^\-]|$)/, PSEUDO : /:((?:[\w\u00c0-\uFFFF\-]|\\.)+)(?:\((['"]?)((?:\([^\)]+\)|[^\(\)]*)+)\2\))?/
                },
                leftMatch : {}, attrMap : {
                    "class" : "className", "for" : "htmlFor"
                },
                attrHandle : 
                {
                    href : function (H)
                    {
                        return H.getAttribute("href");
                    },
                    type : function (H)
                    {
                        return H.getAttribute("type");
                    }
                },
                relative : 
                {
                    "+" : function (N, I)
                    {
                        var K = typeof I === "string", M = K && !G.test(I), O = K && !M;
                        if (M) {
                            I = I.toLowerCase()
                        }
                        for (var J = 0, H = N.length, L; J < H; J++)
                        {
                            if ((L = N[J]))
                            {
                                while ((L = L.previousSibling) && L.nodeType !== 1) {}
                                N[J] = O || L && L.nodeName.toLowerCase() === I ? L || false : L === I;
                            }
                        }
                        if (O) {
                            p.filter(I, N, true)
                        }
                    },
                    ">" : function (N, I)
                    {
                        var M, L = typeof I === "string", J = 0, H = N.length;
                        if (L && !G.test(I))
                        {
                            I = I.toLowerCase();
                            for (; J < H; J++) {
                                M = N[J];
                                if (M) {
                                    var K = M.parentNode;
                                    N[J] = K.nodeName.toLowerCase() === I ? K : false;
                                }
                            }
                        }
                        else
                        {
                            for (; J < H; J++) {
                                M = N[J];
                                if (M) {
                                    N[J] = L ? M.parentNode : M.parentNode === I;
                                }
                            }
                            if (L) {
                                p.filter(I, N, true)
                            }
                        }
                    },
                    "" : function (K, I, M)
                    {
                        var L, J = y++, H = D;
                        if (typeof I === "string" && !G.test(I)) {
                            I = I.toLowerCase();
                            L = I;
                            H = n
                        }
                        H("parentNode", I, J, K, L, M);
                    },
                    "~" : function (K, I, M)
                    {
                        var L, J = y++, H = D;
                        if (typeof I === "string" && !G.test(I)) {
                            I = I.toLowerCase();
                            L = I;
                            H = n
                        }
                        H("previousSibling", I, J, K, L, M);
                    }
                },
                find : 
                {
                    ID : function (I, J, K)
                    {
                        if (typeof J.getElementById !== "undefined" && !K) {
                            var H = J.getElementById(I[1]);
                            return H && H.parentNode ? [H] : [];
                        }
                    },
                    NAME : function (J, M)
                    {
                        if (typeof M.getElementsByName !== "undefined")
                        {
                            var I = [], L = M.getElementsByName(J[1]);
                            for (var K = 0, H = L.length; K < H; K++) {
                                if (L[K].getAttribute("name") === J[1]) {
                                    I.push(L[K])
                                }
                            }
                            return I.length === 0 ? null : I;
                        }
                    },
                    TAG : function (H, I)
                    {
                        if (typeof I.getElementsByTagName !== "undefined") {
                            return I.getElementsByTagName(H[1]);
                        }
                    }
                },
                preFilter : 
                {
                    CLASS : function (K, I, J, H, N, O)
                    {
                        K = " " + K[1].replace(z, "") + " ";
                        if (O) {
                            return K
                        }
                        for (var L = 0, M; (M = I[L]) != null; L++)
                        {
                            if (M)
                            {
                                if (N^(M.className && (" " + M.className + " ").replace(/[\t\n\r]/g, " ").indexOf(K) >= 0)) {
                                    if (!J) {
                                        H.push(M)
                                    }
                                }
                                else {
                                    if (J) {
                                        I[L] = false;
                                    }
                                }
                            }
                        }
                        return false;
                    },
                    ID : function (H)
                    {
                        return H[1].replace(z, "");
                    },
                    TAG : function (I, H)
                    {
                        return I[1].replace(z, "").toLowerCase();
                    },
                    CHILD : function (H)
                    {
                        if (H[1] === "nth")
                        {
                            if (!H[2]) {
                                p.error(H[0])
                            }
                            H[2] = H[2].replace(/^\+|\s*/g, "");
                            var I = /(-?)(\d*)(?:n([+\-]?\d*))?/.exec(H[2] === "even" && "2n" || H[2] === "odd" && "2n+1" || !/\D/.test(H[2]) && "0n+" + H[2] || H[2]);
                            H[2] = (I[1] + (I[2] || 1)) - 0;
                            H[3] = I[3] - 0
                        }
                        else {
                            if (H[2]) {
                                p.error(H[0])
                            }
                        }
                        H[0] = y++;
                        return H;
                    },
                    ATTR : function (L, I, J, H, M, N)
                    {
                        var K = L[1] = L[1].replace(z, "");
                        if (!N && u.attrMap[K]) {
                            L[1] = u.attrMap[K]
                        }
                        L[4] = (L[4] || L[5] || "").replace(z, "");
                        if (L[2] === "~=") {
                            L[4] = " " + L[4] + " "
                        }
                        return L;
                    },
                    PSEUDO : function (L, I, J, H, M)
                    {
                        if (L[1] === "not")
                        {
                            if ((x.exec(L[3]) || "").length > 1 ||/^\w/.test(L[3])) {
                                L[3] = p(L[3], null, null, I)
                            }
                            else {
                                var K = p.filter(L[3], I, J, true^M);
                                if (!J) {
                                    H.push.apply(H, K)
                                }
                                return false;
                            }
                        }
                        else {
                            if (u.match.POS.test(L[0]) || u.match.CHILD.test(L[0])) {
                                return true;
                            }
                        }
                        return L;
                    },
                    POS : function (H)
                    {
                        H.unshift(true);
                        return H;
                    }
                },
                filters : 
                {
                    enabled : function (H)
                    {
                        return H.disabled === false && H.type !== "hidden";
                    },
                    disabled : function (H)
                    {
                        return H.disabled === true;
                    },
                    checked : function (H)
                    {
                        return H.checked === true;
                    },
                    selected : function (H)
                    {
                        if (H.parentNode) {
                            H.parentNode.selectedIndex
                        }
                        return H.selected === true;
                    },
                    parent : function (H)
                    {
                        return !!H.firstChild;
                    },
                    empty : function (H)
                    {
                        return !H.firstChild;
                    },
                    has : function (J, I, H)
                    {
                        return !!p(H[3], J).length;
                    },
                    header : function (H)
                    {
                        return (/h\d/i).test(H.nodeName);
                    },
                    text : function (J)
                    {
                        var H = J.getAttribute("type"), I = J.type;
                        return J.nodeName.toLowerCase() === "input" && "text" === I && (H === I || H === null);
                    },
                    radio : function (H)
                    {
                        return H.nodeName.toLowerCase() === "input" && "radio" === H.type;
                    },
                    checkbox : function (H)
                    {
                        return H.nodeName.toLowerCase() === "input" && "checkbox" === H.type;
                    },
                    file : function (H)
                    {
                        return H.nodeName.toLowerCase() === "input" && "file" === H.type;
                    },
                    password : function (H)
                    {
                        return H.nodeName.toLowerCase() === "input" && "password" === H.type;
                    },
                    submit : function (I)
                    {
                        var H = I.nodeName.toLowerCase();
                        return (H === "input" || H === "button") && "submit" === I.type;
                    },
                    image : function (H)
                    {
                        return H.nodeName.toLowerCase() === "input" && "image" === H.type;
                    },
                    reset : function (I)
                    {
                        var H = I.nodeName.toLowerCase();
                        return (H === "input" || H === "button") && "reset" === I.type;
                    },
                    button : function (I)
                    {
                        var H = I.nodeName.toLowerCase();
                        return H === "input" && "button" === I.type || H === "button";
                    },
                    input : function (H)
                    {
                        return (/input|select|textarea|button/i).test(H.nodeName);
                    },
                    focus : function (H)
                    {
                        return H === H.ownerDocument.activeElement;
                    }
                },
                setFilters : 
                {
                    first : function (I, H)
                    {
                        return H === 0;
                    },
                    last : function (J, I, H, K)
                    {
                        return I === K.length - 1;
                    },
                    even : function (I, H)
                    {
                        return H % 2 === 0;
                    },
                    odd : function (I, H)
                    {
                        return H % 2 === 1;
                    },
                    lt : function (J, I, H)
                    {
                        return I < H[3] - 0;
                    },
                    gt : function (J, I, H)
                    {
                        return I > H[3] - 0;
                    },
                    nth : function (J, I, H)
                    {
                        return H[3] - 0 === I;
                    },
                    eq : function (J, I, H)
                    {
                        return H[3] - 0 === I;
                    }
                },
                filter : 
                {
                    PSEUDO : function (J, O, N, P)
                    {
                        var H = O[1], I = u.filters[H];
                        if (I) {
                            return I(J, N, O, P)
                        }
                        else
                        {
                            if (H === "contains") {
                                return (J.textContent || J.innerText || p.getText([J]) || "").indexOf(O[3]) >= 0
                            }
                            else
                            {
                                if (H === "not") {
                                    var K = O[3];
                                    for (var M = 0, L = K.length; M < L; M++) {
                                        if (K[M] === J) {
                                            return false;
                                        }
                                    }
                                    return true
                                }
                                else {
                                    p.error(H)
                                }
                            }
                        }
                    },
                    CHILD : function (H, K)
                    {
                        var N = K[1], I = H;
                        switch (N)
                        {
                            case "only":
                            case "first":
                                while ((I = I.previousSibling)) {
                                    if (I.nodeType === 1) {
                                        return false;
                                    }
                                }
                                if (N === "first") {
                                    return true
                                }
                                I = H;
                            case "last":
                                while ((I = I.nextSibling)) {
                                    if (I.nodeType === 1) {
                                        return false;
                                    }
                                }
                                return true;
                            case "nth":
                                var J = K[2], Q = K[3];
                                if (J === 1 && Q === 0) {
                                    return true
                                }
                                var M = K[0], P = H.parentNode;
                                if (P && (P.sizcache !== M || !H.nodeIndex))
                                {
                                    var L = 0;
                                    for (I = P.firstChild; I; I = I.nextSibling) {
                                        if (I.nodeType === 1) {
                                            I.nodeIndex =++L
                                        }
                                    }
                                    P.sizcache = M
                                }
                                var O = H.nodeIndex - Q;
                                if (J === 0) {
                                    return O === 0
                                }
                                else {
                                    return (O % J === 0 && O / J >= 0);
                                }
                        }
                    },
                    ID : function (I, H)
                    {
                        return I.nodeType === 1 && I.getAttribute("id") === H;
                    },
                    TAG : function (I, H)
                    {
                        return (H === "*" && I.nodeType === 1) || I.nodeName.toLowerCase() === H;
                    },
                    CLASS : function (I, H)
                    {
                        return (" " + (I.className || I.getAttribute("class")) + " ").indexOf(H) > -1;
                    },
                    ATTR : function (M, K)
                    {
                        var J = K[1], H = u.attrHandle[J] ? u.attrHandle[J](M) : M[J] != null ? M[J] : M.getAttribute(J), 
                        N = H + "", L = K[2], I = K[4];
                        return H == null ? L === "!=" : L === "=" ? N === I : L === "*=" ? N.indexOf(I) >= 0 : L === "~=" ? (" " + N + " ").indexOf(I) >= 0 :!I ? N && H !== false : L === "!=" ? N !== I : L === "^=" ? N.indexOf(I) === 0 : L === "$=" ? N.substr(N.length - I.length) === I : L === "|=" ? N === I || N.substr(0, 
                        I.length + 1) === I + "-" : false
                    },
                    POS : function (L, I, J, M)
                    {
                        var H = I[2], K = u.setFilters[H];
                        if (K) {
                            return K(L, J, I, M);
                        }
                    }
                }
            };
            var t = u.match.POS, o = function (I, H)
            {
                return "\\" + (H - 0 + 1);
            };
            for (var q in u.match)
            {
                u.match[q] = new RegExp(u.match[q].source + (/(?![^\[]*\])(?![^\(]*\))/.source));
                u.leftMatch[q] = new RegExp(/(^(?:.|\r|\n)*?)/.source + u.match[q].source.replace(/\\(\d+)/g, 
                o))
            }
            var v = function (I, H)
            {
                I = Array.prototype.slice.call(I, 0);
                if (H) {
                    H.push.apply(H, I);
                    return H
                }
                return I;
            };
            try {
                Array.prototype.slice.call(document.documentElement.childNodes, 0)[0].nodeType
            }
            catch (E)
            {
                v = function (L, K)
                {
                    var J = 0, I = K || [];
                    if (B.call(L) === "[object Array]") {
                        Array.prototype.push.apply(I, L)
                    }
                    else
                    {
                        if (typeof L.length === "number") {
                            for (var H = L.length; J < H; J++) {
                                I.push(L[J])
                            }
                        }
                        else {
                            for (; L[J]; J++) {
                                I.push(L[J])
                            }
                        }
                    }
                    return I;
                }
            }
            var A, w;
            if (document.documentElement.compareDocumentPosition)
            {
                A = function (I, H)
                {
                    if (I === H) {
                        s = true;
                        return 0
                    }
                    if (!I.compareDocumentPosition || !H.compareDocumentPosition) {
                        return I.compareDocumentPosition ?- 1 : 1
                    }
                    return I.compareDocumentPosition(H) & 4 ?- 1 : 1;
                }
            }
            else
            {
                A = function (P, O)
                {
                    if (P === O) {
                        s = true;
                        return 0
                    }
                    else {
                        if (P.sourceIndex && O.sourceIndex) {
                            return P.sourceIndex - O.sourceIndex;
                        }
                    }
                    var M, I, J = [], H = [], L = P.parentNode, N = O.parentNode, Q = L;
                    if (L === N) {
                        return w(P, O)
                    }
                    else {
                        if (!L) {
                            return - 1
                        }
                        else {
                            if (!N) {
                                return 1;
                            }
                        }
                    }
                    while (Q) {
                        J.unshift(Q);
                        Q = Q.parentNode
                    }
                    Q = N;
                    while (Q) {
                        H.unshift(Q);
                        Q = Q.parentNode
                    }
                    M = J.length;
                    I = H.length;
                    for (var K = 0; K < M && K < I; K++) {
                        if (J[K] !== H[K]) {
                            return w(J[K], H[K]);
                        }
                    }
                    return K === M ? w(P, H[K], - 1) : w(J[K], O, 1);
                };
                w = function (I, H, J)
                {
                    if (I === H) {
                        return J
                    }
                    var K = I.nextSibling;
                    while (K) {
                        if (K === H) {
                            return - 1
                        }
                        K = K.nextSibling
                    }
                    return 1;
                }
            }
            p.getText = function (H)
            {
                var I = "", K;
                for (var J = 0; H[J]; J++)
                {
                    K = H[J];
                    if (K.nodeType === 3 || K.nodeType === 4) {
                        I += K.nodeValue
                    }
                    else {
                        if (K.nodeType !== 8) {
                            I += p.getText(K.childNodes)
                        }
                    }
                }
                return I;
            };
            (function ()
            {
                var I = document.createElement("div"), J = "script" + (new Date()).getTime(), H = document.documentElement;
                I.innerHTML = "<a name='" + J + "'/>";
                H.insertBefore(I, H.firstChild);
                if (document.getElementById(J))
                {
                    u.find.ID = function (L, M, N)
                    {
                        if (typeof M.getElementById !== "undefined" && !N)
                        {
                            var K = M.getElementById(L[1]);
                            return K ? K.id === L[1] || typeof K.getAttributeNode !== "undefined" && K.getAttributeNode("id").nodeValue === L[1] ? [K] : undefined : [];
                        }
                    };
                    u.filter.ID = function (M, K)
                    {
                        var L = typeof M.getAttributeNode !== "undefined" && M.getAttributeNode("id");
                        return M.nodeType === 1 && L && L.nodeValue === K;
                    }
                }
                H.removeChild(I);
                H = I = null;
            })();
            (function ()
            {
                var H = document.createElement("div");
                H.appendChild(document.createComment(""));
                if (H.getElementsByTagName("*").length > 0)
                {
                    u.find.TAG = function (I, M)
                    {
                        var L = M.getElementsByTagName(I[1]);
                        if (I[1] === "*") {
                            var K = [];
                            for (var J = 0; L[J]; J++) {
                                if (L[J].nodeType === 1) {
                                    K.push(L[J])
                                }
                            }
                            L = K
                        }
                        return L;
                    }
                }
                H.innerHTML = "<a href='#'></a>";
                if (H.firstChild && typeof H.firstChild.getAttribute !== "undefined" && H.firstChild.getAttribute("href") !== "#") {
                    u.attrHandle.href = function (I)
                    {
                        return I.getAttribute("href", 2);
                    }
                }
                H = null;
            })();
            if (document.querySelectorAll)
            {
                (function ()
                {
                    var H = p, K = document.createElement("div"), J = "__sizzle__";
                    K.innerHTML = "<p class='TEST'></p>";
                    if (K.querySelectorAll && K.querySelectorAll(".TEST").length === 0) {
                        return
                    }
                    p = function (V, M, Q, U)
                    {
                        M = M || document;
                        if (!U && !p.isXML(M))
                        {
                            var T = /^(\w+$)|^\.([\w\-]+$)|^#([\w\-]+$)/.exec(V);
                            if (T && (M.nodeType === 1 || M.nodeType === 9))
                            {
                                if (T[1]) {
                                    return v(M.getElementsByTagName(V), Q)
                                }
                                else
                                {
                                    if (T[2] && u.find.CLASS && M.getElementsByClassName) {
                                        return v(M.getElementsByClassName(T[2]), Q);
                                    }
                                }
                            }
                            if (M.nodeType === 9)
                            {
                                if (V === "body" && M.body) {
                                    return v([M.body], Q)
                                }
                                else
                                {
                                    if (T && T[3])
                                    {
                                        var P = M.getElementById(T[3]);
                                        if (P && P.parentNode) {
                                            if (P.id === T[3]) {
                                                return v([P], Q);
                                            }
                                        }
                                        else {
                                            return v([], Q);
                                        }
                                    }
                                }
                                try {
                                    return v(M.querySelectorAll(V), Q)
                                }
                                catch (R) {}
                            }
                            else
                            {
                                if (M.nodeType === 1 && M.nodeName.toLowerCase() !== "object")
                                {
                                    var N = M, O = M.getAttribute("id"), L = O || J, X = M.parentNode, 
                                    W = /^\s*[+~]/.test(V);
                                    if (!O) {
                                        M.setAttribute("id", L)
                                    }
                                    else {
                                        L = L.replace(/'/g, "\\$&")
                                    }
                                    if (W && X) {
                                        M = M.parentNode
                                    }
                                    try {
                                        if (!W || X) {
                                            return v(M.querySelectorAll("[id='" + L + "'] " + V), Q);
                                        }
                                    }
                                    catch (S) {}
                                    finally {
                                        if (!O) {
                                            N.removeAttribute("id")
                                        }
                                    }
                                }
                            }
                        }
                        return H(V, M, Q, U);
                    };
                    for (var I in H) {
                        p[I] = H[I]
                    }
                    K = null;
                })()
            }
            (function ()
            {
                var H = document.documentElement, J = H.matchesSelector || H.mozMatchesSelector || H.webkitMatchesSelector || H.msMatchesSelector;
                if (J)
                {
                    var L = !J.call(document.createElement("div"), "div"), I = false;
                    try {
                        J.call(document.documentElement, "[test!='']:sizzle")
                    }
                    catch (K) {
                        I = true
                    }
                    p.matchesSelector = function (N, P)
                    {
                        P = P.replace(/\=\s*([^'"\]]*)\s*\]/g, "='$1']");
                        if (!p.isXML(N))
                        {
                            try
                            {
                                if (I || !u.match.PSEUDO.test(P) && !/!=/.test(P)) {
                                    var M = J.call(N, P);
                                    if (M || !L || N.document && N.document.nodeType !== 11) {
                                        return M;
                                    }
                                }
                            }
                            catch (O) {}
                        }
                        return p(P, null, null, [N]).length > 0;
                    }
                }
            })();
            (function ()
            {
                var H = document.createElement("div");
                H.innerHTML = "<div class='test e'></div><div class='test'></div>";
                if (!H.getElementsByClassName || H.getElementsByClassName("e").length === 0) {
                    return
                }
                H.lastChild.className = "e";
                if (H.getElementsByClassName("e").length === 1) {
                    return
                }
                u.order.splice(1, 0, "CLASS");
                u.find.CLASS = function (I, J, K)
                {
                    if (typeof J.getElementsByClassName !== "undefined" && !K) {
                        return J.getElementsByClassName(I[1]);
                    }
                };
                H = null;
            })();
            function n(I, N, M, Q, O, P)
            {
                for (var K = 0, J = Q.length; K < J; K++)
                {
                    var H = Q[K];
                    if (H)
                    {
                        var L = false;
                        H = H[I];
                        while (H)
                        {
                            if (H.sizcache === M) {
                                L = Q[H.sizset];
                                break
                            }
                            if (H.nodeType === 1 && !P) {
                                H.sizcache = M;
                                H.sizset = K
                            }
                            if (H.nodeName.toLowerCase() === N) {
                                L = H;
                                break
                            }
                            H = H[I]
                        }
                        Q[K] = L;
                    }
                }
            }
            function D(I, N, M, Q, O, P)
            {
                for (var K = 0, J = Q.length; K < J; K++)
                {
                    var H = Q[K];
                    if (H)
                    {
                        var L = false;
                        H = H[I];
                        while (H)
                        {
                            if (H.sizcache === M) {
                                L = Q[H.sizset];
                                break
                            }
                            if (H.nodeType === 1)
                            {
                                if (!P) {
                                    H.sizcache = M;
                                    H.sizset = K
                                }
                                if (typeof N !== "string") {
                                    if (H === N) {
                                        L = true;
                                        break
                                    }
                                }
                                else {
                                    if (p.filter(N, [H]).length > 0) {
                                        L = H;
                                        break
                                    }
                                }
                            }
                            H = H[I]
                        }
                        Q[K] = L;
                    }
                }
            }
            if (document.documentElement.contains) {
                p.contains = function (I, H)
                {
                    return I !== H && (I.contains ? I.contains(H) : true);
                }
            }
            else
            {
                if (document.documentElement.compareDocumentPosition) {
                    p.contains = function (I, H)
                    {
                        return!!(I.compareDocumentPosition(H) & 16)
                    }
                }
                else {
                    p.contains = function ()
                    {
                        return false;
                    }
                }
            }
            p.isXML = function (H)
            {
                var I = (H ? H.ownerDocument || H : 0).documentElement;
                return I ? I.nodeName !== "HTML" : false;
            };
            var C = function (H, O)
            {
                var M, K = [], L = "", J = O.nodeType ? [O] : O;
                while ((M = u.match.PSEUDO.exec(H))) {
                    L += M[0];
                    H = H.replace(u.match.PSEUDO, "")
                }
                H = u.relative[H] ? H + "*" : H;
                for (var N = 0, I = J.length; N < I; N++) {
                    p(H, J[N], K)
                }
                return p.filter(L, K);
            };
            window.Sizzle = p;
            return
        })();
        g._original_property = this.Sizzle;
        g.Selector = (function (p)
        {
            function q(w, x, t)
            {
                t = t || 0;
                var s = g.Selector.match, v = w.length, r = 0, u;
                for (u = 0; u < v; u++) {
                    if (s(w[u], x) && t == r++) {
                        return g(w[u]);
                    }
                }
            }
            function n(r, s, t)
            {
                return m.doms(p(r, s || document), t, r)
            }
            function o(s, r)
            {
                return p.matches(r, [s]).length == 1
            }
            return {
                select : n, match : o, find : q
            }
        })(Sizzle);
        this.Sizzle = g._original_property;
        delete g._original_property
    })();
    (function ()
    {
        var s = g.typeOf = function (w)
        {
            if (w == null) {
                return "null"
            }
            if (typeof w) {
                if (w.Kfamily) {
                    return w.Kfamily();
                }
            }
            if (w.nodeName)
            {
                if (w.nodeType == 1) {
                    return "element"
                }
                if (w.nodeType == 3) {
                    return (/\S/).test(w.nodeValue) ? "textnode" : "whitespace";
                }
            }
            else
            {
                if (typeof w.length == "number") {
                    if (w.callee) {
                        return "arguments"
                    }
                    if ("item"in w) {
                        return "collection";
                    }
                }
            }
            return typeof w;
        };
        var m = g.instanceOf = function (y, w)
        {
            if (y == null) {
                return false
            }
            var x = y.$constructor || y.constructor;
            while (x) {
                if (x === w) {
                    return true
                }
                x = x.parent
            }
            return y instanceof w;
        };
        var l = this.Function;
        var t = true;
        for (var n in {
            toString : 1
        }) {
            t = null
        }
        if (t)
        {
            t = ["hasOwnProperty", "valueOf", "isPrototypeOf", "propertyIsEnumerable", "toLocaleString", 
            "toString", "constructor"]
        }
        l.prototype.KoverloadSetter = function (x)
        {
            var w = this;
            return function (z, y)
            {
                if (z == null) {
                    return this
                }
                if (x || typeof z != "string")
                {
                    for (var A in z) {
                        w.call(this, A, z[A])
                    }
                    if (t) {
                        for (var B = t.length; B--; ) {
                            A = t[B];
                            if (z.hasOwnProperty(A)) {
                                w.call(this, A, z[A])
                            }
                        }
                    }
                }
                else {
                    w.call(this, z, y)
                }
                return this;
            }
        };
        l.prototype.Kextend = function (w, x)
        {
            this [w] = x
        }
        .KoverloadSetter();
        l.prototype.Kimplement = function (w, x)
        {
            this.prototype[w] = x
        }
        .KoverloadSetter();
        l.from = function (w)
        {
            return (s(w) == "function") ? w : function () {
                return w;
            }
        };
        var q = Array.prototype.slice;
        l.Kimplement(
        {
            Khide : function ()
            {
                this.$hidden = true;
                return this;
            },
            Kprotect : function ()
            {
                this.$protected = true;
                return this;
            }
        });
        var o = g.Type = function (z, y)
        {
            if (z)
            {
                var x = z.toLowerCase();
                var w = function (A)
                {
                    return (s(A) == x);
                };
                o["is" + z] = w;
                if (y != null) {
                    y.prototype.Kfamily = (function ()
                    {
                        return x;
                    }).Khide();
                    y.type = w;
                }
            }
            if (y == null) {
                return null
            }
            y.Kextend(this);
            y.$constructor = o;
            y.prototype.$constructor = y;
            return y;
        };
        var k = Object.prototype.toString;
        o.isEnumerable = function (w)
        {
            return (w != null && typeof w.length == "number" && k.call(w) != "[object Function]");
        };
        var u = {};
        var v = function (w)
        {
            var x = s(w.prototype);
            return u[x] || (u[x] = []);
        };
        var r = function (x, B)
        {
            if (B && B.$hidden) {
                return
            }
            var w = v(this);
            for (var y = 0; y < w.length; y++) {
                var A = w[y];
                if (s(A) == "type") {
                    r.call(A, x, B)
                }
                else {
                    A.call(this, x, B)
                }
            }
            var z = this.prototype[x];
            if (z == null || !z.$protected) {
                this.prototype[x] = B
            }
            if (this [x] == null && s(B) == "function")
            {
                p.call(this, x, function (C)
                {
                    return B.apply(C, q.call(arguments, 1));
                })
            }
        };
        var p = function (w, y)
        {
            if (y && y.$hidden) {
                return
            }
            var x = this [w];
            if (x == null || !x.$protected) {
                this [w] = y;
            }
        };
        o.Kimplement(
        {
            Kimplement : r.KoverloadSetter(), extend : p.KoverloadSetter(),
            alias : function (w, x)
            {
                r.call(this, w, this.prototype[x])
            }
            .KoverloadSetter(),
            mirror : function (w)
            {
                v(this).push(w);
                return this;
            }
        });
        new o("KType", o);
        RegExp.prototype.Kfamily = function ()
        {
            return "regexp";
        };
        Date.prototype.Kfamily = function ()
        {
            return "date";
        }
    })();
    var d = this.KClass = g.fn = (function ()
    {
        var n = (function ()
        {
            for (var o in {
                toString : 1
            }) {
                if (o === "toString") {
                    return false;
                }
            }
            return true;
        })();
        function k() {}
        function l()
        {
            var r = null, q = KA(arguments);
            if (g.C.isFunction(q[0])) {
                r = q.shift()
            }
            function o()
            {
                this.init.apply(this, arguments)
            }
            o.prototype.Kfamily = function ()
            {
                return "KClass";
            };
            g.C.Kextend(o, d.Methods);
            o.superclass = r;
            o.subclasses = [];
            if (r) {
                k.prototype = r.prototype;
                o.prototype = new k;
                r.subclasses.push(o)
            }
            for (var p = 0, s = q.length; p < s; p++) {
                o.addMethods(q[p])
            }
            if (!o.prototype.init) {
                o.prototype.init = function () {}
            }
            o.prototype.constructor = o;
            return o
        }
        function m(u)
        {
            var q = this.superclass && this.superclass.prototype, p = g.C.keys(u);
            if (n)
            {
                if (u.toString != Object.prototype.toString) {
                    p.push("toString")
                }
                if (u.valueOf != Object.prototype.valueOf) {
                    p.push("valueOf")
                }
            }
            for (var o = 0, r = p.length; o < r; o++)
            {
                var t = p[o], s = u[t];
                if (q && g.C.isFunction(s) && s.KargumentNames()[0] == "Ksuper")
                {
                    var v = s;
                    s = (function (w)
                    {
                        return function ()
                        {
                            return q[w].apply(this, arguments);
                        }
                    })(t).Kwrap(v);
                    s.valueOf = v.valueOf.Kbind(v);
                    s.toString = v.toString.Kbind(v)
                }
                this.prototype[t] = s
            }
            return this
        }
        return {
            create : l, Methods : {
                addMethods : m
            }
        }
    })();
    (function ()
    {
        g.C = g.O = {};
        var J = Object.prototype.toString, L = "Null", N = "Undefined", Y = "Boolean", G = "Number", E = "String", 
        X = "Object", q = "[object Function]", l = "[object Boolean]", r = "[object Number]", m = "[object String]", 
        k = "[object Array]", U = "[object Date]";
        function o(aa, Z)
        {
            var ac = typeof (aa) == "object" && aa != null;
            if (ac && V(Z)) {
                for (var ab in obj) {
                    return!!Z
                }
            }
            return ac
        }
        function V(Z)
        {
            return Z !== undefined
        }
        function M(Z)
        {
            return Z === undefined
        }
        function u(Z)
        {
            return o(Z) && Z.nodeType === 1 && !!Z.nodeName
        }
        function n(Z)
        {
            return u(Z) || Z == _win || Z == _doc
        }
        function I(Z)
        {
            return o(Z) && Z.isKdom === true
        }
        function D(Z)
        {
            return o(Z) && Z.isKdoms === true
        }
        function P(Z)
        {
            return o(Z) && V(Z.length)
        }
        function W(Z)
        {
            return o(Z) && V(Z.length) && Z.length > 0 && u(Z[0])
        }
        function K(Z)
        {
            return n(Z) ? Z : p(Z) ? (_doc.getElementById(Z) || _doc.getElementsByName(Z)[0]) : null
        }
        function z(Z)
        {
            return A({}, Z)
        }
        function R(Z)
        {
            return!!(Z && Z.nodeType == 1)
        }
        function C(Z)
        {
            return J.call(Z) === k
        }
        function v(Z)
        {
            return J.call(Z) === q
        }
        function p(Z)
        {
            return J.call(Z) === m
        }
        function w(Z)
        {
            return Z && Z.toHTML ? Z.toHTML() : g.S.interpret(Z)
        }
        function T(Z)
        {
            return J.call(Z) === r
        }
        function B(Z)
        {
            return J.call(Z) === U
        }
        function x(Z)
        {
            return typeof Z === "undefined"
        }
        function y(Z)
        {
            return Z instanceof i
        }
        function H(Z)
        {
            if (Q(Z) !== X) {
                throw new TypeError()
            }
            var aa = [];
            for (var ab in Z) {
                if (Z.hasOwnProperty(ab)) {
                    aa.push(ab)
                }
            }
            return aa
        }
        function t(Z, ab)
        {
            for (var aa in Z) {
                if (hasOwnProperty.call(Z, aa) && Z[aa] === ab) {
                    return aa;
                }
            }
            return null
        }
        function Q(aa)
        {
            switch (aa) {
                case null:
                    return L;
                case (void 0):
                    return N
            }
            var Z = typeof aa;
            switch (Z) {
                case "boolean":
                    return Y;
                case "number":
                    return G;
                case "string":
                    return E
            }
            return X
        }
        function s(Z)
        {
            try
            {
                if (x(Z)) {
                    return "undefined"
                }
                if (Z === null) {
                    return "null"
                }
                return Z.inspect ? Z.inspect() : String(Z)
            }
            catch (aa) {
                if (aa instanceof RangeError) {
                    return "error"
                }
                throw aa
            }
        }
        function A(Z, ab)
        {
            for (var aa in ab) {
                Z[aa] = ab[aa]
            }
            return Z
        }
        function S(Z)
        {
            return p(Z) ? new Function("a", "b", "c", "return " + Z) : Z
        }
        function O(Z)
        {
            return (new g.H(Z)).toQueryString()
        }
        A(g.C, 
        {
            _Type : Q, isObj : o, isset : V, unset : M, isnode : u, isDom : n, isKdom : I, isKdoms : D, 
            iscollect : P, isDoms : W, node : K, Kextend : A, clone : z, toHTML : w, inspect : s, isElement : R, 
            isArray : C, isarr : C, isHash : y, isFunction : v, isfun : v, isString : p, isstr : p, isNumber : T, 
            isnum : T, isDate : B, keys : H, keyOf : t, isUndefined : x, lambda : S, toQueryString : O
        })
    })();
    g.C.Kextend(Function.prototype, (function ()
    {
        var p = Array.prototype.slice;
        function o(x, w)
        {
            var u = p.call(arguments, 2);
            var v = w;
            var t = x;
            if (g.C.isfun(w)) {
                v = x;
                t = w
            }
            else {
                if (g.C.isstr(w)) {
                    v = x;
                    t = v[w];
                }
            }
            return function ()
            {
                return t.apply(v, u.concat([].slice.call(arguments)));
            }
        }
        function s()
        {
            var t = this.toString().match(/^[\s\(]*function[^(]*\(([^)]*)\)/)[1].replace(/\/\/.*?[\r\n]|\/\*(?:.|[\r\n])*?\*\//g, 
            "").replace(/\s+/g, "").split(",");
            return t.length == 1 && !t[0] ? [] : t
        }
        function l(v)
        {
            if (arguments.length < 2 && g.C.isUndefined(arguments[0])) {
                return this
            }
            var t = this, u = p.call(arguments, 1);
            return function ()
            {
                var w = r(u, arguments);
                return t.apply(v, w);
            }
        }
        function n(u)
        {
            var t = this;
            return function ()
            {
                var v = k([t.Kbind(this)], arguments);
                return u.apply(this, v);
            }
        }
        function q()
        {
            if (!arguments.length) {
                return this
            }
            var t = this, u = p.call(arguments, 0);
            return function ()
            {
                var v = r(u, arguments);
                return t.apply(this, v);
            }
        }
        function k(w, t)
        {
            var v = w.length, u = t.length;
            while (u--) {
                w[v + u] = t[u]
            }
            return w
        }
        function r(u, t)
        {
            u = p.call(u, 0);
            return k(u, t)
        }
        function m(v)
        {
            var t = this, u = p.call(arguments, 1);
            v = v * 1000;
            return window.setTimeout(function ()
            {
                return t.apply(t, u);
            }, v)
        }
        return {
            Kproxy : o, KargumentNames : s, KargNames : s, Kbind : l, Kwrap : n, Kcurry : q, Kdelay : m
        }
    })());
    (function ()
    {
        g.S = g.String = g.fn.create();
        g.S.Kextend(
        {
            interpret : function (l)
            {
                return l == null ? "" : String(l);
            },
            trim : function (l)
            {
                return String(l).replace(/^\s+|\s+$/g, "");
            },
            include : function (l, m)
            {
                return l.indexOf(m) > -1;
            }
        });
        function k(m, n)
        {
            var l = g.S.trim(m).match(/([^?#]*)(#.*)?$/);
            if (!l) {
                return{}
            }
            return g.A.each(l[1].split(n || "&"), function (r, s, o)
            {
                if ((r = r.split("="))[0])
                {
                    var p = decodeURIComponent(r.shift()), q = r.length > 1 ? r.join("=") : r[0];
                    if (q != undefined) {
                        q = decodeURIComponent(q)
                    }
                    if (p in r) {
                        if (!g.C.isArray(r[p])) {
                            r[p] = [r[p]]
                        }
                        o[p].push(q)
                    }
                    else {
                        o[p] = q;
                    }
                }
            },
            {})
        }
        g.O.Kextend(g.S, {
            toQueryParams : k
        })
    })();
    var c = (function ()
    {
        function s(x, w)
        {
            var v = 0;
            try {
                this._each(function (z)
                {
                    x.call(w, z, v++)
                })
            }
            catch (y) {
                if (y != {}) {
                    throw y
                }
            }
            return this
        }
        function p(y, x, w)
        {
            var v =- y, z = [], A = this.toArray();
            if (y < 1) {
                return A
            }
            while ((v += y) < A.length) {
                z.push(A.slice(v, v + y))
            }
            return z.collect(x, w)
        }
        function q(x, w)
        {
            x = x || Prototype.K;
            var v = [];
            this.each(function (z, y)
            {
                v.push(x.call(w, z, y))
            });
            return v
        }
        function k(x, w)
        {
            var v;
            this.each(function (z, y)
            {
                if (x.call(w, z, y)) {
                    v = z;
                    throw $break
                }
            });
            return v
        }
        function r(x, w)
        {
            var v = [];
            this.each(function (z, y)
            {
                if (x.call(w, z, y)) {
                    v.push(z)
                }
            });
            return v
        }
        function o(v, x, w)
        {
            this.each(function (z, y)
            {
                v = x.call(w, v, z, y);
            });
            return v
        }
        function m(w)
        {
            var v = $A(arguments).slice(1);
            return this.map(function (x)
            {
                return x[w].apply(x, v);
            })
        }
        function n(w)
        {
            var v = [];
            this.each(function (x)
            {
                v.push(x[w])
            });
            return v
        }
        function u(x, w)
        {
            var v = [];
            this.each(function (z, y)
            {
                if (!x.call(w, z, y)) {
                    v.push(z)
                }
            });
            return v
        }
        function l()
        {
            return this.map()
        }
        function t()
        {
            return this.toArray().length
        }
        return {
            each : s, eachSlice : p, collect : q, map : q, detect : k, findAll : r, select : r, filter : r, 
            inject : o, invoke : m, pluck : n, reject : u, size : t, find : k
        }
    })();
    var i = g.H = d.create(c, (function ()
    {
        function p(q)
        {
            this._object = o(q) ? q.toObject() : g.O.clone(q)
        }
        function k(r)
        {
            for (var q in this._object) {
                var s = this._object[q], t = [q, s];
                t.key = q;
                t.value = s;
                r(t)
            }
        }
        function o(q)
        {
            return q instanceof i
        }
        function m()
        {
            return g.O.clone(this._object)
        }
        function l(q, r)
        {
            if (g.O.isUndefined(r)) {
                return q
            }
            return q + "=" + encodeURIComponent(g.S.interpret(r))
        }
        function n()
        {
            return this.inject([], function (u, x)
            {
                var t = encodeURIComponent(x.key), r = x.value;
                if (r && typeof r == "object")
                {
                    if (g.O.isArray(r)) {
                        var w = [];
                        for (var s = 0, q = r.length, v; s < q; s++) {
                            v = r[s];
                            w.push(l(t, v))
                        }
                        return u.concat(w);
                    }
                }
                else {
                    u.push(l(t, r))
                }
                return u;
            }).join("&")
        }
        return {
            init : p, _each : k, isHash : o, toObject : m, toQueryString : n
        }
    })());
    (function ()
    {
        var l = navigator.userAgent.toLowerCase(), k = navigator.platform.toLowerCase(), m = l.match(/(opera|ie|firefox|chrome|version)[\s\/:]([\w\d\.]+)?.*?(safari|version[\s\/:]([\w\d\.]+)|$)/) || [null, 
        "unknown", 0], p = m[1] == "ie" && document.documentMode;
        var o = g.fn.create(
        {
            name : (m[1] == "version") ? m[3] : m[1], version : p || parseFloat((m[1] == "opera" && m[4]) ? m[4] : m[2]), 
            Platform : 
            {
                name : l.match(/ip(?:ad|od|hone)/) ? "ios" : (l.match(/(?:webos|android)/) || k.match(/mac|win|linux/) || ["other"])[0]
            },
            Features : 
            {
                xpath :!!(document.evaluate), air :!!(window.runtime), query :!!(document.querySelector), 
                json :!!(window.JSON)
            },
            Plugins : {}
        });
        g.B = g.Browser = new o();
        g.B = g.Browser = 
        {
            name : (m[1] == "version") ? m[3] : m[1], version : p || parseFloat((m[1] == "opera" && m[4]) ? m[4] : m[2]), 
            Platform : 
            {
                name : l.match(/ip(?:ad|od|hone)/) ? "ios" : (l.match(/(?:webos|android)/) || k.match(/mac|win|linux/) || ["other"])[0]
            },
            Features : 
            {
                xpath :!!(document.evaluate), air :!!(window.runtime), query :!!(document.querySelector), 
                json :!!(window.JSON)
            },
            Plugins : {}
        };
        g.B[g.B.name] = true;
        if (g.B.version == 5) {
            g.B.version = 8
        }
        g.B[g.B.name + parseInt(g.B.version, 10)] = true;
        g.B.Platform[g.B.Platform.name] = true;
        if (g.B.Platform.ios) {
            g.B.Platform.ipod = true
        }
        g.B.Engine = {};
        var n = function (r, q)
        {
            g.B.Engine.name = r;
            g.B.Engine[r + q] = true;
            g.B.Engine.version = q;
        };
        if (g.B.ie)
        {
            g.B.Engine.trident = true;
            switch (g.B.version)
            {
                case 6:
                    n("trident", 4);
                    break;
                case 7:
                    n("trident", 5);
                    break;
                case 8:
                    n("trident", 6);
                    break;
                case 9:
                    n("trident", 7)
            }
        }
        if (g.B.firefox) {
            g.B.Engine.gecko = true;
            if (g.B.version >= 3) {
                n("gecko", 19)
            }
            else {
                n("gecko", 18)
            }
        }
        if (g.B.safari || g.B.chrome)
        {
            g.B.Engine.webkit = true;
            switch (g.B.version) {
                case 2:
                    n("webkit", 419);
                    break;
                case 3:
                    n("webkit", 420);
                    break;
                case 4:
                    n("webkit", 525)
            }
        }
        if (g.B.opera)
        {
            g.B.Engine.presto = true;
            if (g.B.version >= 9.6) {
                n("presto", 960)
            }
            else {
                if (g.B.version >= 9.5) {
                    n("presto", 950)
                }
                else {
                    n("presto", 925)
                }
            }
        }
        if (g.B.name == "unknown")
        {
            switch ((l.match(/(?:webkit|khtml|gecko)/) || [])[0])
            {
                case "webkit":
                case "khtml":
                    g.B.Engine.webkit = true;
                    break;
                case "gecko":
                    g.B.Engine.gecko = true;
            }
        }
    })();
    (function ()
    {
        g.A = g.Array = g.fn.create();
        g.A.Kextend(
        {
            each : function (o, q, p)
            {
                if (g.C.isArray(o)) {
                    var p = p || [];
                    for (var k = 0, m = o.length; k < m; k++) {
                        q(o[k], k, p)
                    }
                }
                else
                {
                    if (g.C.isNumber(o)) {
                        var p = p || [];
                        for (var k = 0, m = o; k < m; k++) {
                            q(k, p)
                        }
                    }
                    else {
                        var p = p || {};
                        for (var n in o) {
                            q(o[n], n, p)
                        }
                    }
                }
                if (p) {
                    return p;
                }
            },
            map : function (n, o)
            {
                var l = [], k = n.length;
                if (o) {
                    o = g.C.lambda(o);
                    for (var m = 0; m < k; m++) {
                        l[m] = o(n[m], m);
                    }
                }
                else {
                    for (var m = 0; m < k; m++) {
                        l[m] = n[m];
                    }
                }
                return l;
            }
        })
    })();
    (function ()
    {
        var l = {};
        var k = g.DOMEvent = g.E = new g.Type("DOMEvent", function (m, q)
        {
            if (!q) {
                q = window
            }
            m = m || q.event;
            if (m.$extended) {
                return m
            }
            this.event = m;
            this.$extended = true;
            this.shift = m.shiftKey;
            this.control = m.ctrlKey;
            this.alt = m.altKey;
            this.meta = m.metaKey;
            var s = this.type = m.type;
            var r = m.target || m.srcElement;
            while (r && r.nodeType == 3) {
                r = r.parentNode
            }
            this.target = g(r).node;
            if (s.indexOf("key") == 0)
            {
                var n = this.code = (m.which || m.keyCode);
                this.key = l[n] || g.O.keyOf(m.Keys, n);
                if (s == "keydown") {
                    if (n > 111 && n < 124) {
                        this.key = "f" + (n - 111)
                    }
                    else {
                        if (n > 95 && n < 106) {
                            this.key = n - 96;
                        }
                    }
                }
                if (this.key == null) {
                    this.key = String.fromCharCode(n).toLowerCase();
                }
            }
            else
            {
                if (s == "click" || s == "dblclick" || s == "contextmenu" || s.indexOf("mouse") == 0)
                {
                    var t = q.document;
                    t = (!t.compatMode || t.compatMode == "CSS1Compat") ? t.html : t.body;
                    this.page = {
                        x : m.clientX, y : m.clientY
                    };
                    this.client = 
                    {
                        x : (m.pageX != null) ? m.pageX - q.pageXOffset : m.clientX, y : (m.pageY != null) ? m.pageY - q.pageYOffset : m.clientY
                    };
                    if (s == "DOMMouseScroll" || s == "mousewheel") {
                        this.wheel = (m.wheelDelta) ? m.wheelDelta / 120 :- (m.detail || 0) / 3
                    }
                    this.rightClick = (m.which == 3 || m.button == 2);
                    if (s == "mouseover" || s == "mouseout")
                    {
                        var u = m.relatedTarget || m[(s == "mouseover" ? "from" : "to") + "Element"];
                        while (u && u.nodeType == 3) {
                            u = u.parentNode
                        }
                        this.relatedTarget = g(u).node;
                    }
                }
                else
                {
                    if (s.indexOf("touch") == 0 || s.indexOf("gesture") == 0)
                    {
                        this.rotation = m.rotation;
                        this.scale = m.scale;
                        this.targetTouches = m.targetTouches;
                        this.changedTouches = m.changedTouches;
                        var p = this.touches = m.touches;
                        if (p && p[0]) {
                            var o = p[0];
                            this.page = {
                                x : o.pageX, y : o.pageY
                            };
                            this.client = {
                                x : o.clientX, y : o.clientY
                            }
                        }
                    }
                }
            }
            if (!this.client) {
                this.client = {}
            }
            if (!this.page) {
                this.page = {}
            }
        });
        k.Kimplement(
        {
            stopPropagation : function ()
            {
                if (this.event.stopPropagation) {
                    this.event.stopPropagation()
                }
                else {
                    this.event.cancelBubble = true
                }
                return this;
            },
            preventDefault : function ()
            {
                if (this.event.preventDefault) {
                    this.event.preventDefault()
                }
                else {
                    this.event.returnValue = false
                }
                return this;
            }
        });
        k.defineKey = function (n, m)
        {
            l[n] = m;
            return this;
        };
        k.defineKeys = k.defineKey.KoverloadSetter(true);
        k.defineKeys(
        {
            "38" : "up", "40" : "down", "37" : "left", "39" : "right", "27" : "esc", "32" : "space", "8" : "backspace", 
            "9" : "tab", "46" : "delete", "13" : "enter"
        })
    })();
    this.KEvent = g.E;
    (function ()
    {
        g.R = g.Regexp = g.fn.create();
        g.R.Kextend({
            num : /^\-?\d+(?:\.\d+)?$/
        })
    })();
    (function ()
    {
        var k = this.Kwdom = g.fn.create(
        {
            parent : function (l)
            {
                return Koala.each(function (o, n, p)
                {
                    if (!p) {
                        p = n
                    }
                    p = p || 1;
                    o = o.node;
                    for (var m = 0; m < p; m++) {
                        o = o.parentNode
                    }
                    return g(o);
                }, this, l)
            },
            sibling : function (l)
            {
                return Koala.each(function (q, m)
                {
                    var p = Function.prototype.Kproxy;
                    if (g.C.isNumber(m))
                    {
                        var o = m > 0 ? "nextSibling" : "previousSibling";
                        m = Math.abs(m);
                        var q = q.node;
                        while (q = q[o]) {
                            if (q.nodeType == 1 && (--m == 0)) {
                                return g(q);
                            }
                        }
                        return false
                    }
                    var n = [];
                    (g.C.unset(m) ? "<>" : m).replace(/./g, p(q, function (s)
                    {
                        s = s == ">" ? "nextSibling" : "previousSibling";
                        var r = this.node;
                        while (r = r[s]) {
                            r.nodeType == 1 && n.unshift(r)
                        }
                    }));
                    if (!n[0][0]) {
                        n = [n]
                    }
                    return KK.doms(n);
                }, this, l)
            },
            prev : function ()
            {
                return this.sibling(-1);
            },
            next : function ()
            {
                return this.sibling(1);
            },
            child : function (l)
            {
                return Koala.each(function (s, q, m)
                {
                    if (!m) {
                        m = q
                    }
                    if (m.length == 0)
                    {
                        var p = [];
                        for (var o = s.node.childNodes, n = o.length, q = 0; q < n; q++) {
                            o[q].nodeType == 1 && p.push(o[q])
                        }
                        return KK.doms(p)
                    }
                    var r = function (y, v)
                    {
                        var w = y.childNodes;
                        var u = w.length;
                        var t =+ v.shift();
                        var x;
                        if (t < 0) {
                            for (x = u - 1; x >= 0; x--) {
                                if (w[x].nodeType == 1 &&++t == 0) {
                                    break
                                }
                            }
                        }
                        else {
                            for (x = 0; x < u; x++) {
                                if (w[x].nodeType == 1 &&--t < 0) {
                                    break
                                }
                            }
                        }
                        if (x < 0 || x >= u) {
                            return false
                        }
                        return v.length > 0 ? r(w[x], v) : g(w[x]);
                    };
                    return g(r(s.node, g.A.map(m)));
                }, this, arguments)
            },
            first : function ()
            {
                return this.child(0);
            },
            last : function ()
            {
                return this.child(-1);
            },
            append : function (l)
            {
                if (this.isKdom)
                {
                    if (g.C.isString(l)) {
                        this.node.appendChild(g.fragment(l))
                    }
                    else
                    {
                        if (g.C.isElement(l.node)) {
                            this.node.appendChild(l.node)
                        }
                        else {
                            this.node.appendChild(l)
                        }
                    }
                }
                else
                {
                    if (this.isKdoms)
                    {
                        this.each(function (o, m, n)
                        {
                            if (g.C.isString(n)) {
                                o.node.appendChild(g.fragment(n))
                            }
                            else {
                                if (g.C.isElement(n)) {
                                    el = n.cloneNode(true);
                                    o.node.appendChild(n)
                                }
                            }
                        }, l)
                    }
                }
                return this;
            },
            remove : function ()
            {
                return Koala.each(function (m, l)
                {
                    m.parent().node.removeChild(m.node)
                }, this)
            },
            empty : function (l)
            {
                return Koala.each(function (o)
                {
                    if (g.C.unset(l)) {
                        while (o.node.firstChild) {
                            o.node.removeChild(o.node.firstChild)
                        }
                    }
                    else
                    {
                        for (var m = o.node.childNodes, n = m.length - 1; n >= 0; n--) {
                            m[n].nodeType != l && o.node.removeChild(m[n])
                        }
                    }
                    return o;
                }, this)
            },
            getByTagName : function (l)
            {
                var q = this.node.getElementsByTagName(l);
                try {
                    return [].slice.call(q)
                }
                catch (p) {
                    var n, o = 0, m = [];
                    while (n = q[o]) {
                        m[o++] = n
                    }
                    return m;
                }
            },
            insert : function (o)
            {
                element = this.node;
                if (g.C.isstr(o) || g.C.isNumber(o) || g.C.isElement(o) || (o && (o.toElement || o.toHTML))) {
                    o = {
                        bottom : o
                    }
                }
                var n, p, m, q;
                for (var l in o)
                {
                    n = o[l];
                    if (g.C.isfun(n)) {
                        continue
                    }
                    l = l.toLowerCase();
                    p = g._insertionTranslations[l];
                    if (n && n.toElement) {
                        n = n.toElement()
                    }
                    if (g.C.isElement(n)) {
                        this.insert(element, n)
                    }
                    n = g.C.toHTML(n);
                    m = ((l == "before" || l == "after") ? element.parentNode : element).tagName.toUpperCase();
                    q = g._getContentFromAnonymousElement(m, n);
                    if (l == "top" || l == "after") {
                        q.reverse()
                    }
                    g.A.each(q, p.Kcurry(element))
                }
                return g(this);
            },
            cleanWhitespace : function ()
            {
                return Koala.each(function (o)
                {
                    var n = o.node;
                    var m = o.node.firstChild;
                    while (m)
                    {
                        var l = m.nextSibling;
                        if (m.nodeType == 3 && !/\S/.test(m.nodeValue)) {
                            n.removeChild(m)
                        }
                        m = l
                    }
                    return g(o);
                }, this)
            }
        });
        k.Kimplement(
        {
            classNames : function ()
            {
                return Koala.each(function (l)
                {
                    return l.node.className;
                }, this)
            },
            hasClass : function (l)
            {
                return Koala.each(function (n)
                {
                    var m = n.node.className;
                    return (m.length > 0 && (m == l || new RegExp("(^|\\s)" + l + "(\\s|$)").test(m)));
                }, this)
            },
            addClass : function (l)
            {
                return Koala.each(function (n, m)
                {
                    if (!n.hasClass(l)) {
                        n.node.className += (n.node.className ? " " : "") + l
                    }
                    return g(n);
                }, this)
            },
            removeClass : function (l)
            {
                return Koala.each(function (n)
                {
                    var m = g.S.trim;
                    n.node.className = m(n.node.className.replace(new RegExp("(^|\\s+)" + l + "(\\s+|$)"), 
                    " "));
                    return n;
                }, this)
            },
            toggleClass : function (l)
            {
                return this [this.hasClass(l) ? "removeClass" : "addClass"](l);
            },
            find : function (l)
            {
                return Koala.each(function (o, n, m)
                {
                    if (!m) {
                        m = n
                    }
                    var p = KA([m]).join(", ");
                    return g.Selector.select(p, o.node);
                }, this, l)
            },
            scrollLeft : function (l)
            {
                return Koala.each(function (o)
                {
                    if (g.C.isset(l))
                    {
                        if (o.tag == "win" || o.tag == "body")
                        {
                            var n = g().bind("_load", function ()
                            {
                                document.documentElement.scrollLeft = l;
                                document.body.scrollLeft = l;
                            });
                            var m = g().bind("_keydown", function (p)
                            {
                                if (p.code == 116 && g.B.chrome || g.B.safari || g.B.Opera) {
                                    document.documentElement.scrollLeft = l;
                                    document.body.scrollLeft = l;
                                }
                            })
                        }
                        else {
                            o.node.scrollLeft = l
                        }
                        return o
                    }
                    else
                    {
                        if (o.tag == "win" || o.tag == "body") {
                            return document.documentElement.scrollLeft || document.body.scrollLeft
                        }
                        else {
                            return o.node.scrollLeft;
                        }
                    }
                }, this)
            },
            scrollTop : function (l)
            {
                return Koala.each(function (o)
                {
                    if (g.C.isset(l))
                    {
                        if (o.tag == "win" || o.tag == "body")
                        {
                            var n = g().bind("_load", function ()
                            {
                                document.documentElement.scrollTop = l;
                                document.body.scrollTop = l;
                            });
                            var m = g().bind("_keydown", function (p)
                            {
                                if (p.code == 116 && g.B.chrome || g.B.safari || g.B.Opera) {
                                    document.documentElement.scrollTop = l;
                                    document.body.scrollTop = l;
                                }
                            })
                        }
                        else {
                            o.node.scrollTop = l
                        }
                        return o
                    }
                    else
                    {
                        if (o.tag == "win" || o.tag == "body") {
                            return document.documentElement.scrollTop || document.body.scrollTop
                        }
                        else {
                            return o.node.scrollTop;
                        }
                    }
                }, this)
            },
            show : function ()
            {
                return this.css("display", /^(span|img|a|input|b|u|i|label|strong|em)$/.test(this.tag) === false ? "block" : "inline");
            },
            hide : function ()
            {
                return this.css("display", "none");
            },
            toggle : function ()
            {
                this.css("display") == "none" ? this.show() : this.hide()
            },
            css : function (m, l)
            {
                return Koala.each(function (r, o)
                {
                    var n = g.S.trim;
                    if (g.C.isObj(m)) {
                        for (var p in m) {
                            r.css(p, m[p])
                        }
                        return g(r.node)
                    }
                    if (m.indexOf(":") > -1)
                    {
                        g.A.each(m.replace(/;$/, "").split(";"), Function.prototype.Kproxy(function (u)
                        {
                            var t = u.split(":");
                            r.css(n(t.shift()), n(t.join(":")))
                        }, r));
                        return g(r.node)
                    }
                    if (/\-\w/.test(m)) {
                        m = m.replace(/\-(\w)/, function (u, t)
                        {
                            return t.toUpperCase();
                        })
                    }
                    if (o.length == 1)
                    {
                        if (r.node == document || r.node == window) {
                            r.node = document.body
                        }
                        return r.node.style[m] || (_doc.defaultView ? _doc.defaultView.getComputedStyle(r.node, 
                        null)[m] : r.node.currentStyle ? r.node.currentStyle[m] : "")
                    }
                    try {
                        if (l == "rgb(NaN,NaN,NaN)") {
                            l = "transparent";
                            r.node.style[m] = l
                        }
                        r.node.style[m] = l
                    }
                    catch (q) {}
                    return r;
                }, this, arguments)
            },
            getStyle : function (l)
            {
                return this.css(l);
            },
            setStyle : function (m, l)
            {
                return this.css(m, l);
            },
            opacity : function (l)
            {
                return Koala.each(function (o, m, p)
                {
                    if (!p) {
                        p = m
                    }
                    if (p)
                    {
                        if (o.node.style.opacity != undefined) {
                            return o.css("opacity", p)
                        }
                        return o.css("filter", "alpha(opacity=" + p * 100 + ")")
                    }
                    if (o.node.style.opacity != undefined) {
                        return g.R.num.test(o.css("opacity")) ?+ RegExp.lastMatch : 1
                    }
                    return / alpha\(opacity = (\d + )\) / .test(o.css("filter")) ? RegExp.$1 / 100 : 1;
                }, this, l)
            },
            html : function (l)
            {
                if (g.C.unset(l))
                {
                    if (this.isKdom) {
                        return this.node.innerHTML
                    }
                    else {
                        if (this.isKdoms) {
                            return g.A.each(this.data[0], function (o, n, m)
                            {
                                m[n] = o.innerHTML;
                            })
                        }
                    }
                }
                return Koala.each(function (o)
                {
                    switch (o.tag)
                    {
                        case "select":
                            if (Browser.ie)
                            {
                                o.empty();
                                var o = _doc.createElement("div");
                                o.innerHTML = "<select>" + l + "</select>";
                                var m = o.firstChild.childNodes;
                                while (m.length > 0) {
                                    o.node.appendChild(m[0])
                                }
                            }
                            else {
                                o.node.innerHTML = l
                            }
                            if (arguments.length == 2) {
                                o.node.value = arguments[1]
                            }
                            break;
                        case "table":
                            o.find("tbody").item(0).html(l);
                            break;
                        case "thead":
                        case "tfoot":
                        case "tbody":
                            o.empty();
                            var p = _doc.createElement("div");
                            p.innerHTML = "<table><tbody>" + l + "</tbody></table>";
                            var n = p.firstChild.tBodies[0].rows;
                            while (n.length > 0) {
                                o.node.appendChild(n[0])
                            }
                            break;
                        default:
                            o.node.innerHTML = l;
                            break
                    }
                    return o;
                }, this)
            },
            attr : function (m, l)
            {
                return Koala.each(function (t, s, p)
                {
                    if (!p) {
                        p = s
                    }
                    var v = p.length;
                    if (v == 2)
                    {
                        if (m == "style") {
                            t.node.style.cssText = l
                        }
                        else {
                            if (t.node[m] != undefined) {
                                t.node[m] = l
                            }
                            else {
                                t.node.setAttribute(m, l)
                            }
                        }
                        return t
                    }
                    if (v == 1)
                    {
                        if (typeof m == "object") {
                            for (var s in m) {
                                t.attr(s, m[s])
                            }
                            return t
                        }
                        if (m.indexOf("=") > -1)
                        {
                            F.each(F.trim(m).split(/\s+/), F.proxy(function (o)
                            {
                                var n = o.split("=");
                                t.attr(F.trim(n[0]), /["'](.+?)["']/.test(n[1]) ? RegExp.$1 : F.trim(n[1]))
                            }, t));
                            return t
                        }
                        if (m == "style") {
                            return t.node.style.cssText
                        }
                        else
                        {
                            if (m == "href" && t.tag == "a") {
                                return t.node.getAttribute(m, 2)
                            }
                            else
                            {
                                if (m == "src") {
                                    return t.node.getAttribute(m, 2)
                                }
                                else {
                                    if (t.node[m] != undefined) {
                                        return t.node[m];
                                    }
                                }
                            }
                        }
                        return t.node.getAttribute(m)
                    }
                    if (v == 0)
                    {
                        var u = {};
                        for (var r = t.node.attributes, q = r.length, s = 0; s < q; s++) {
                            u[r[s].name] = r[s].value
                        }
                        return u;
                    }
                }, this, arguments)
            },
            bind : function (n, l, m)
            {
                return Koala.each(function (r)
                {
                    n = n.replace(/^_/, "");
                    var p = r;
                    var q = q || r;
                    var o = function (s)
                    {
                        if (!s) {
                            s = window.e
                        }
                        l.call(q, new g.E(s), p);
                    };
                    if (r.node.addEventListener) {
                        r.node.addEventListener(n, o, false)
                    }
                    else
                    {
                        if (r.node.attachEvent) {
                            if (r.tag == "win") {
                                r.node.attachEvent("on" + n, o)
                            }
                            else {
                                r.node.attachEvent("on" + n, o)
                            }
                        }
                    }
                    return RegExp.lastMatch == "_" ? o : r;
                }, this)
            },
            unbind : function (m, l)
            {
                return Koala.each(function (n)
                {
                    if (_win.removeEventListener) {
                        n.node.removeEventListener(m, l, false)
                    }
                    else {
                        if (_win.attachEvent) {
                            n.node.detachEvent("on" + m, l)
                        }
                    }
                    return n;
                }, this)
            },
            click : function (l, m)
            {
                return this.bind("click", l, m);
            },
            hover : function (l, m)
            {
                if (arguments.length == 1) {
                    this.bind("mouseover", l)
                }
                else {
                    this.bind("mouseover", l);
                    this.bind("mouseout", m)
                }
                return this;
            }
        })
    })();
    (function ()
    {
        g.P = Kwdom.prototype;
        g.P.fn = function () {};
        g.P.fn.Kextend({
            extend : function (k)
            {
                Kwdom.addMethods(k)
            }
        })
    })();
    g.P.fn.extend(
    {
        px : function (k, l)
        {
            if (arguments.length == 2) {
                this.node.style[k] = l + "px";
                return this
            }
            return parseInt(this.css(k), 10) || 0;
        },
        width : function (k)
        {
            if (arguments.length == 1) {
                return this.px("width", k)
            }
            return this.px("width") || this.node.offsetWidth - this.px("paddingLeft") - this.px("paddingRight") - this.px("borderLeftWidth") - this.px("borderRightWidth");
        },
        height : function (k)
        {
            if (arguments.length == 1) {
                return this.px("height", k)
            }
            return this.px("height") || this.node.offsetHeight - this.px("paddingTop") - this.px("paddingBottom") - this.px("borderTopWidth") - this.px("borderBottomWidth");
        },
        pageX : function (l)
        {
            var k;
            l ? k = l : k = this.node;
            return k.offsetParent ? k.offsetLeft + Kwdom.prototype.pageX(k.offsetParent) : k.offsetLeft;
        },
        pageY : function (l)
        {
            var k;
            l ? k = l : k = this.node;
            return k.offsetParent ? k.offsetTop + Kwdom.prototype.pageY(k.offsetParent) : k.offsetTop;
        },
        parentX : function (l)
        {
            var k;
            l ? k = l : k = this.node;
            return k.parentNode == k.offsetParent ? k.offsetLeft : Kwdom.prototype.pageX(k) - Kwdom.prototype.pageX(k.parentNode);
        },
        parentY : function (l)
        {
            var k;
            l ? k = l : k = this.node;
            return k.parentNode == k.offsetParent ? k.offsetTop : Kwdom.prototype.pageY(k) - Kwdom.prototype.pageY(k.parentNode);
        },
        Left : function (k)
        {
            if (arguments.length == 1) {
                this.node.style.left = k + "px"
            }
            return this.px("left");
        },
        Top : function (k)
        {
            if (arguments.length == 1) {
                this.node.style.top = k + "px"
            }
            return this.px("top");
        }
    });
    g.P.clone = function (k)
    {
        function m(n, p)
        {
            for (var o in p) {
                n[o] = p[o]
            }
            return n
        }
        if (!!k) {
            var l = m({}, this);
            return g(l.node.cloneNode(true))
        }
        else {
            return this;
        }
    };
    g.P.fn.extend(
    {
        parentWrap : function (k)
        {
            return Koala.each(function (n)
            {
                var o = n.clone(true);
                var m = document.createElement("div");
                m.innerHTML = k;
                var l = m.cloneNode(true);
                while (l.firstChild && l.firstChild.nodeType == 1) {
                    l = l.firstChild
                }
                l.appendChild(o.node);
                n.node.parentNode.insertBefore(l);
                n.node.parentNode.removeChild(n.node)
            }, this)
        },
        childWrap : function (l, k)
        {
            return Koala.each(function (o)
            {
                if (k) {
                    o = o.child(k)
                }
                var n = o.html();
                o.empty();
                o.html(l);
                var m = o.node;
                while (m.firstChild) {
                    m = m.firstChild
                }
                m.innerHTML = n;
            }, this)
        }
    });
    Koala.each = function (n, m, k)
    {
        var l = n.KargNames();
        if (m.isKdom) {
            if (l[l.length - 1] == "r") {
                n(m, k);
                return g(m.node)
            }
            else {
                return n(m, k);
            }
        }
        else
        {
            if (m.isKdoms) {
                if (l[l.length - 1] == "r") {
                    m.each(n, k);
                    return KK(m.exp)
                }
                else {
                    return m._each(n, k);
                }
            }
        }
    };
    g._insertionTranslations = 
    {
        before : function (k, l)
        {
            k.parentNode.insertBefore(l, k)
        },
        top : function (k, l)
        {
            k.insertBefore(l, k.firstChild)
        },
        bottom : function (k, l)
        {
            k.appendChild(l)
        },
        after : function (k, l)
        {
            k.parentNode.insertBefore(l, k.nextSibling)
        },
        tags : 
        {
            TABLE : ["<table>", "</table>", 1], TBODY : ["<table><tbody>", "</tbody></table>", 2], TR : ["<table><tbody><tr>", 
            "</tr></tbody></table>", 3], TD : ["<table><tbody><tr><td>", "</td></tr></tbody></table>", 
            4], SELECT : ["<select>", "</select>", 1]
        }
    };
    g._getContentFromAnonymousElement = function (o, n, p)
    {
        var q = document.createElement("div");
        var m = g._insertionTranslations.tags[o];
        var k = false;
        if (m) {
            k = true
        }
        else {
            if (p) {
                k = true;
                m = ["", "", 0];
            }
        }
        if (k)
        {
            q.innerHTML = "&nbsp;" + m[0] + n + m[1];
            q.removeChild(q.firstChild);
            for (var l = m[2]; l--; ) {
                q = q.firstChild;
            }
        }
        else {
            q.innerHTML = n
        }
        return KA(q.childNodes);
    };
    g.fragment = function (o)
    {
        var l = [], m = _doc.createElement("div"), s = _doc.createDocumentFragment();
        m.innerHTML = o;
        var n = m.childNodes;
        for (var q = 0, t = n.length; q < t; q++) {
            l[l.length] = n[q]
        }
        for (var p = 0, u = l.length; p < u; p++) {
            s.appendChild(l[p])
        }
        return s;
    };
    g.extend = function (k, l)
    {
        if (!l) {
            g.O.Kextend(g, k)
        }
        else {
            return g.O.Kextend(k, l);
        }
    };
    g.ready = function (k)
    {
        if (g.C.isfun(k)) {
            return j(k)
        }
        k.init && j(Function.prototype.Kproxy(k, k.init));
        return k;
    };
    var j = function ()
    {
        var n = false, o = [], p, k = function (q)
        {
            if (n) {
                q()
            }
            else {
                o.push(q)
            }
        },
        l = function ()
        {
            for (var r = 0, q = o.length; r < q; r++) {
                o[r]()
            }
            o = null;
        },
        m = function (q)
        {
            if (n) {
                return
            }
            n = true;
            l();
            if (_doc.removeEventListener) {
                _doc.removeEventListener("DOMContentLoaded", m, false)
            }
            else
            {
                if (_doc.attachEvent)
                {
                    _doc.detachEvent("onreadystatechange", m);
                    if (_win == _win.top) {
                        clearInterval(p);
                        p = null;
                    }
                }
            }
        };
        if (_doc.addEventListener) {
            _doc.addEventListener("DOMContentLoaded", m, false)
        }
        else
        {
            if (_doc.attachEvent)
            {
                _doc.attachEvent("onreadystatechange", function ()
                {
                    if ((/loaded|complete/).test(_doc.readyState)) {
                        m()
                    }
                });
                if (_win == _win.top)
                {
                    p = setInterval(function ()
                    {
                        try {
                            n || _doc.docElement.doScroll("left")
                        }
                        catch (q) {
                            return
                        }
                        m()
                    }, 5)
                }
            }
        }
        return k
    }();
    var f = this.ClassK = d.create(Kwdom, 
    {
        init : function (k)
        {
            this.isKdom = true;
            this.node = k;
            this.exist = true;
            this.tag = this.node == _win ? "win" : this.node == _doc ? "doc" : this.node.tagName.toLowerCase();
        },
        Version : "1.4.1", author : "boqiu",
        item : function (k)
        {
            return this;
        },
        nitem : function (k)
        {
            return this.node;
        },
        noConflict : function ()
        {
            if (this.Version) {
                window.K = e
            }
            return _K;
        },
        each : function (k)
        {
            k(this, 0);
            return this;
        }
    });
    var h = d.create(f, 
    {
        init : function (k)
        {
            if (h.comp) {
                return false
            }
            if (h.debug) {
                this.errorEle = k;
                console.log('Error: the "' + this.errorEle + '" not bind')
            }
        }
    });
    g.compatible = function (k)
    {
        h.comp = k;
    };
    g.debug = function (k)
    {
        h.debug = k;
        if (g.Browser.ie) {
            window.console = function () {};
            console.log = log = function (l)
            {
                alert(l)
            }
        }
    };
    var b = this.Knative = d.create(Kwdom, 
    {
        init : function (k, l)
        {
            this.data = k;
            this.len = k[0].length;
            this.length = k[0].length;
            this.isKdoms = true;
            this.exist = true;
            this.exp = l || "null";
            k[1] = k[1] || "null";
            return this;
        },
        item : function (k)
        {
            if (k < 0) {
                k += this.len
            }
            return g(this.data[0][k]);
        },
        nitem : function (k)
        {
            if (k < 0) {
                k += this.len
            }
            return this.data[0][k];
        },
        each : function (p, m)
        {
            var n = this, l = this.len;
            for (var k = 0; k < l; k++) {
                if (this.data[0][k] == undefined) {
                    continue
                }
                else {
                    p(g(n.item(k)), k, m)
                }
            }
            return this;
        },
        _check : function (k)
        {
            for (var m = 0; m < k.length; m++) {
                for (var l = m + 1; l < k.length; l++) {
                    if (k[l] === k[m]) {
                        k.splice(l, 1);
                        l--
                    }
                }
            }
            return k;
        },
        _each : function (t, u)
        {
            var k = this, p = this.len, r = [], n = [], l = [], v = [];
            for (var s = 0; s < p; s++)
            {
                r[s] = t(g(k.item(s)), s, u);
                if (r[s].isKdom) {
                    l[s] = r[s].node
                }
                else {
                    if (r[s].isKdoms) {
                        l[s] = r[s].data[0];
                    }
                }
            }
            if (r[0].isKdom)
            {
                for (var m = 0, p = r.length; m < p; m++)
                {
                    var q = r[m].node;
                    if (!q.id && !q.className) {
                        v[m] = r[m].tag
                    }
                    else
                    {
                        if (q.id && !q.className) {
                            v[m] = "#" + q.id

                        }
                        else
                        {
                            if (q.className && !q.id) {
                                v[m] = "." + q.className
                            }
                            else {
                                if (q.id && q.className) {
                                    v[m] = "." + q.id;
                                }
                            }
                        }
                    }
                }
                v = k._check(v).join(", ")
            }
            else
            {
                if (r[0].isKdoms)
                {
                    for (var m = 0, p = r.length; m < p; m++)
                    {
                        var q = r[m].data[0];
                        if (!q.id && !q.className) {
                            v[m] = r[m].tag
                        }
                        else
                        {
                            if (q.id && !q.className) {
                                v[m] = "#" + q.id
                            }
                            else
                            {
                                if (q.className && !q.id) {
                                    v[m] = "." + q.className
                                }
                                else {
                                    if (q.id && q.className) {
                                        v[m] = "." + q.id;
                                    }
                                }
                            }
                        }
                    }
                    v = k._check(v).join(", ");
                }
            }
            n.push(l);
            return new b(n, v);
        },
        toString : function ()
        {
            return "Kdoms";
        }
    })
})();
(function ()
{
    var b = document, i = {}, e = {}, h = function (k)
    {
        return k.constructor === Array;
    },
    g = {
        core_lib : ["http://mat1.gtimg.com/joke/Koala/Koala.min.1.3.3.js"], mods : {}
    },
    j = b.getElementsByTagName("script")[0], d = function (l, p, r, k, o)
    {
        if (!l) {
            return
        }
        if (i[l]) {
            e[l] = false;
            if (k) {
                k(l, o)
            }
            return
        }
        if (e[l]) {
            setTimeout(function ()
            {
                d(l, p, r, k, o)
            }, 1);
            return
        }
        e[l] = true;
        var q, m = p || l.toLowerCase().substring(l.lastIndexOf(".") + 1);
        if (m === "js")
        {
            q = b.createElement("script");
            q.setAttribute("type", "text/javascript");
            q.setAttribute("src", l);
            q.setAttribute("async", true)
        }
        else
        {
            if (m === "css")
            {
                q = b.createElement("link");
                q.setAttribute("type", "text/css");
                q.setAttribute("rel", "stylesheet");
                q.setAttribute("href", l);
                i[l] = true;
            }
        }
        if (r) {
            q.charset = r
        }
        if (m === "css") {
            j.parentNode.insertBefore(q, j);
            if (k) {
                k(l, o)
            }
            return
        }
        q.onload = q.onreadystatechange = function ()
        {
            if (!this.readyState || this.readyState === "loaded" || this.readyState === "complete")
            {
                i[this.getAttribute("src")] = true;
                if (k) {
                    k(this.getAttribute("src"), o)
                }
                q.onload = q.onreadystatechange = null;
            }
        };
        j.parentNode.insertBefore(q, j)
    },
    c = function (r)
    {
        if (!r || !h(r)) {
            return
        }
        var n = 0, q, l = [], p = g.mods, k = [], m = {}, o = function (v)
        {
            var u = 0, s, t;
            if (m[v]) {
                return k
            }
            m[v] = true;
            if (p[v].requires) {
                t = p[v].requires;
                for (; s = t[u++]; ) {
                    if (p[s]) {
                        o(s);
                        k.push(s)
                    }
                    else {
                        k.push(s)
                    }
                }
                return k
            }
            return k;
        };
        for (; q = r[n++]; ) {
            if (p[q] && p[q].requires && p[q].requires[0]) {
                k = [];
                m = {};
                l = l.concat(o(q))
            }
            l.push(q)
        }
        return l;
    },
    f = function (k)
    {
        if (!k || !h(k)) {
            return
        }
        this.queue = k;
        this.current = null;
    };
    f.prototype = 
    {
        _interval : 10,
        start : function ()
        {
            var k = this;
            this.current = this.next();
            if (!this.current) {
                this.end = true;
                return
            }
            this.run()
        },
        run : function ()
        {
            var m = this, k, l = this.current;
            if (typeof l === "function")
            {
                l();
                this.start();
                return
            }
            else
            {
                if (typeof l === "string")
                {
                    if (g.mods[l]) {
                        k = g.mods[l];
                        d(k.path, k.type, k.charset, function (n)
                        {
                            m.start()
                        }, m)
                    }
                    else
                    {
                        if (/\.js|\.css/i.test(l)) {
                            d(l, "", "", function (n, p)
                            {
                                p.start()
                            }, m)
                        }
                        else {
                            this.start()
                        }
                    }
                }
            }
        },
        next : function ()
        {
            return this.queue.shift();
        }
    };
    this.Qfast = function ()
    {
        var l = Array.prototype.slice.call(arguments, 1), k;
        if (arguments[0]) {
            k = new f(c(g.core_lib.concat(l)))
        }
        else {
            k = new f(c(l))
        }
        k.start();
    };
    this.Qfast.add = function (l, k)
    {
        if (!l || !k || !k.path) {
            return
        }
        g.mods[l] = k;
    }
})();
