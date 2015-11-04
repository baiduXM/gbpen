function copyLink() {
	try {
		var d = document.location.toString();
		if (window.clipboardData) {
			window.clipboardData.setData("Text", d);
			alert("页面链接复制成功，你可以粘贴到飞信、QQ或MSN发送给好友")
		} else {
			if (window.netscape) {
				try {
					netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect")
				} catch (g) {
					alert("您的浏览器设置为不允许复制！\n如果需要此操作，请在浏览器地址栏输入'about:config'并回车\n然后将'signed.applets.codebase_principal_support'设置为'true',再重试复制操作!");
					return false
				}
				var c = Components.classes["@mozilla.org/widget/clipboard;1"].createInstance(Components.interfaces.nsIClipboard);
				if (!c) {
					return
				}
				var b = Components.classes["@mozilla.org/widget/transferable;1"].createInstance(Components.interfaces.nsITransferable);
				if (!b) {
					return
				}
				b.addDataFlavor("text/unicode");
				var h = new Object();
				var a = new Object();
				var h = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
				h.data = d;
				b.setTransferData("text/unicode", h, d.length * 2);
				var f = Components.interfaces.nsIClipboard;
				if (!c) {
					return false
				}
				c.setData(b, null, f.kGlobalClipboard);
				alert("页面链接复制成功，你可以粘贴到飞信、QQ或MSN发送给好友")
			}
		}
	} catch (g) {}
}
var shareid = "fenxiang";
(function() {
	var b = {
		url: function() {
			return encodeURIComponent(window.location.href)
		},
		title: function() {
			return encodeURIComponent(window.document.title)
		},
		content: function(a) {
			if (a) {
				return encodeURIComponent($("#" + a).html())
			} else {
				return ""
			}
		},
		setid: function() {
			if (typeof(shareid) == "undefined") {
				return null
			} else {
				return shareid
			}
		},
		kaixin: function() {
			window.open("http://www.kaixin001.com/repaste/share.php?rtitle=" + this.title() + "&rurl=" + this.url() + "&rcontent=" + this.content(this.setid()))
		},
		renren: function() {
			window.open("http://share.renren.com/share/buttonshare.do?link=" + this.url() + "&title=" + this.title())
		},
		sinaminiblog: function() {
			window.open("http://v.t.sina.com.cn/share/share.php?url=" + this.url() + "&title=" + this.title() + "&content=utf-8&source=&sourceUrl=&pic=")
		},
		baidusoucang: function() {
			window.open("http://cang.baidu.com/do/add?it=" + this.title() + "&iu=" + this.url() + "&dc=" + this.content(this.setid()) + "&fr=ien#nw=1")
		},
		taojianghu: function() {
			window.open("http://share.jianghu.taobao.com/share/addShare.htm?title=" + this.title() + "&url=" + this.url() + "&content=" + this.content(this.setid()))
		},
		wangyi: function() {
			window.open("http://t.163.com/article/user/checkLogin.do?source=%E7%BD%91%E6%98%93%E6%96%B0%E9%97%BB%20%20%20&link=" + this.url() + "&info=" + this.content(this.setid()))
		},
		qqzone: function() {
			window.open("http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=" + encodeURIComponent(location.href) + "&title=" + encodeURIComponent(document.title))
		},
		pengyou: function() {
			window.open("http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?to=pengyou&url=" + encodeURIComponent(location.href) + "&title=" + encodeURIComponent(document.title))
		},
		douban: function() {
			window.open("http://www.douban.com/recommend/?url=" + this.url() + "&title=" + this.title() + "&v=1")
		}
	};
	window.share = b
})();
$(function() {
	$(".renren").click(function() {
		share.renren()
	});
	$(".xinlang").click(function() {
		share.sinaminiblog()
	});
	$(".douban").click(function() {
		share.douban()
	});
	$(".kaixin").click(function() {
		share.kaixin()
	});
	$(".taojianghu").click(function() {
		share.taojianghu()
	});
	$(".wangyi").click(function() {
		share.wangyi()
	});
	$(".qqzone").click(function() {
		share.qqzone()
	});
	$(".baidusoucang").click(function() {
		share.baidusoucang()
	});
	$(".qqweibo").click(function() {
		share.qqweibo()
	});
	$(".qqpengyou").click(function() {
		share.pengyou()
	})
});

function postToWb() {
	var d = encodeURI(document.title);
	var a = encodeURI(document.location);
	var c = encodeURI("appkey");
	var b = "http://v.t.qq.com/share/share.php?title=" + d + "&url=" + a + "&appkey=" + c;
	window.open(b)
};