<div class="input-group">
    <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
    <input type="text" name="address" value="" class="form-control " placeholder="请选择详细地址" readonly="readonly"   id="address">
</div>

<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=LQuB1rkcTHwLVUgScSfNuKN8VA9jxGGN" ></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/EventWrapper/1.2/src/EventWrapper.js" ></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/GeoUtils/1.2/src/GeoUtils.js" ></script>
<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<link href="/css/jquery.baidumapaddress.css" rel="stylesheet" />
<script src="/js/coordtransform.js"></script>
<script src="/js/jquery.baidumapaddress.js"></script>
<script>
    $("#address").baidumapaddress({
        dobackcall: function (location, wgs, baidu) {
            // alert("地址：" + address + "\r\n WGS84坐标：经度：" + wgs.lng + "，纬度：" + wgs.lat + "\r\n 百度坐标：" + "经度："+baidu.lng+"，纬度："+baidu.lat);
        }
    });
</script>
