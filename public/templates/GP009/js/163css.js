var browse = window.navigator.appName.toLowerCase();

var myObj;

var waitTime = 3500;//延长时间
//var spec = 1; //每次滚动的间距, 越大滚动越快
var minOpa = 50; //滤镜最小值
var maxOpa = 100; //滤镜最大值
var spa = 2; //缩略图区域补充数值
var w = 0;

//临时标示，图片数量
var flag = 0;
var flag2 = 0;

//spec = (browse.indexOf("microsoft") > -1) ? spec : ((browse.indexOf("opera") > -1) ? spec * 10 : spec * 20);
function $(e) { return document.getElementById(e); }
//function goleft() { $('photos').scrollLeft -= spec; }
//function goright() { $('photos').scrollLeft += spec; }
function setOpacity(e, n) {

    if (e) {
        if (browse.indexOf("microsoft") > -1) {
            e.style.filter = 'alpha(opacity=' + n + ')';
        }
        else {
            e.style.opacity = n / 100;
        }
    }
}
$('goleft').style.cursor = 'pointer';
$('goright').style.cursor = 'pointer';
$('mainphoto').style.cursor = 'pointer';

$('goleft').onmouseout = function() {
myObj = setInterval(goright2, waitTime);
}
$('goleft').onmouseover = function() {
clearInterval(myObj);
}



$('goleft').onclick = function() {

clearInterval(myObj);

    if (flag > 0) {
        flag--;

    }
    if (flag < flag2) {
        flag--;
        flag2 = 0;
    }
    var divImg = $("divImg").getElementsByTagName('img');

    //当是第三个图片的时候才开始滚动
    if (flag >= 2) {
        $('photos').scrollLeft -= 107;
    }


    if (flag < divImg.length) {

        var p = $('showArea').getElementsByTagName('img');
        //设置前一个不透明
        setOpacity(divImg[flag + 1], minOpa);
        setOpacity(p[flag + 1], minOpa);

        SetShowImgAndText(divImg, p);

    } else {
        flag--;
    }

}
$('goright').onmouseover = function() { clearInterval(myObj); }
$('goright').onmouseout = function() { myObj = setInterval(goright2, waitTime); }
$('goright').onclick = function() {

    var divImg = $("divImg").getElementsByTagName('img');

    goright2();

    clearInterval(myObj);

    if (flag >= divImg.length) {

        myObj = setInterval(goright2, waitTime);
    }

}



window.onload = function() {
    var rHtml = '';
    var p = $('showArea').getElementsByTagName('img');
    for (var i = 0; i < p.length; i++) {

        w += parseInt(p[i].getAttribute('width')) + spa;

        setOpacity(p[i], minOpa);

        p[i].onclick = function() { }
        p[i].style.cursor = 'pointer';
        p[i].onmouseover = function() {

            setOpacity(this, maxOpa);
            $('mainphoto').src = this.getAttribute('rel');
            $('divText').innerText = this.getAttribute('alt');
            $('mainphoto').setAttribute('name', this.getAttribute('name'));

            $("newPage").href = this.getAttribute('name');

            clearInterval(myObj);
        }
        p[i].onmouseout = function() {
            setOpacity(this, minOpa);
            myObj = setInterval(goright2, waitTime);
        }
        rHtml += '<img src="' + p[i].getAttribute('rel') + '" name="' + p[i].getAttribute('name') + '" width="0" height="0" alt="" />';
        rHtml += '<span >' + p[i].getAttribute('alt') + '</span>';
    }

    $('showArea').style.width = parseInt(w) + 'px';

    var rLoad = document.createElement("div");
    $('photos').appendChild(rLoad);
    rLoad.id = "divImg";
    rLoad.style.width = "1px";
    rLoad.style.height = "1px";
    rLoad.style.overflow = "hidden";
    rLoad.innerHTML = rHtml;

}

//设置该显示的大图、小图和文字
function SetShowImgAndText(pDaTu, pXiaoTu) {
    //设置大图透明
    setOpacity(pDaTu[flag], maxOpa);
    //设置小图透明
    setOpacity(pXiaoTu[flag], maxOpa);

    //填充大图
    $('mainphoto').src = pDaTu[flag].getAttribute('src');
    //大图链接
    $("newPage").href = pDaTu[flag].getAttribute("name");

    //填充文字
    var spanText = $("divImg").getElementsByTagName('span');
    $('divText').innerHTML = spanText[flag].innerHTML;
}

function goright2() {

    var divImg = $("divImg");
    if (divImg) {
        clearInterval(myObj);

        divImg = divImg.getElementsByTagName('img');
        if (flag >= divImg.length) {
            flag = 0;
        }
        if (flag < 2) {
            $('photos').scrollLeft = 0;
        }

        //如果右边是最后一个图片，设置右边按钮不可用
        if (flag == divImg.length - 1)
            $('goright').disabled = "disabled";
        else
            $('goright').disabled = "";

        //当是第三个图片的时候才开始滚动
        if (flag > 2) {

            if ($('photos').scrollLeft < (divImg.length - 3) * 107)
                $('photos').scrollLeft += 107;
        }
        else {
            $('photos').scrollLeft = 0;
        }



        var p = $('showArea').getElementsByTagName('img');


        if (flag < divImg.length) {
            if (flag > 0) {

                setOpacity(divImg[flag - 1], minOpa);
                setOpacity(p[flag - 1], minOpa);
            }
            SetShowImgAndText(divImg, p);

            flag++;
        }


        flag2 = flag;
        myObj = setInterval(goright2, 2000);
    }
}
myObj = setInterval(goright2, 10); //比如加在这个地方
