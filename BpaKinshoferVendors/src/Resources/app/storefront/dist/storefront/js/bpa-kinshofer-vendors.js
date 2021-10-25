(window.webpackJsonp = window.webpackJsonp || []).push([["bpa-kinshofer-vendors"], {
    IjV8: function (t, e, n) {
        "use strict";
        n.r(e);
        var o = n("FGIj");
        n("k8s9"), n("3rxU");

        function r(t) {
            return (r = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (t) {
                return typeof t
            } : function (t) {
                return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
            })(t)
        }

        function u(t, e) {
            if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
        }

        function i(t, e) {
            for (var n = 0; n < e.length; n++) {
                var o = e[n];
                o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(t, o.key, o)
            }
        }

        function c(t, e) {
            return !e || "object" !== r(e) && "function" != typeof e ? function (t) {
                if (void 0 === t) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                return t
            }(t) : e
        }

        function a(t) {
            return (a = Object.setPrototypeOf ? Object.getPrototypeOf : function (t) {
                return t.__proto__ || Object.getPrototypeOf(t)
            })(t)
        }

        function f(t, e) {
            return (f = Object.setPrototypeOf || function (t, e) {
                return t.__proto__ = e, t
            })(t, e)
        }

        var s = function (t) {
            function e() {
                return u(this, e), c(this, a(e).apply(this, arguments))
            }

            var n, o, r;
            return function (t, e) {
                if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function");
                t.prototype = Object.create(e && e.prototype, {
                    constructor: {
                        value: t,
                        writable: !0,
                        configurable: !0
                    }
                }), e && f(t, e)
            }(e, t), n = e, (o = [{
                key: "init", value: function () {
                    var t = "label dsa";
                    document.getElementsByClassName("dealer-info-btn").value = t, document.getElementById("store-label").textContent = t, document.getElementById("store-street").textContent = "street", document.getElementById("store-street-number").textContent = "312", document.getElementById("store-country").textContent = "country"
                }
            }]) && i(n.prototype, o), r && i(n, r), e
        }(o.a);
        window.PluginManager.register("BpaQcfDealers", s)
    }
}, [["IjV8", "runtime", "vendor-node", "vendor-shared"]]]);