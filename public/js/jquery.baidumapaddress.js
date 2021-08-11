//百度地图选取点
(function ($, window, document, undefined) {

    //配置参数
    var defaults = {
        lng: '',
        lat: '',
        marker: null,
        mapid: '',
        bdlng: 0,
        bdlng: 0,
        address: '',
        city: "郑州",
        title: '地图选点',
        dobackcall:null,
    };
    var map;

    var BaiduMapAddress = function (element, options) {
        //全局变量
        var opts = options,//配置
			$obj = $(element);//容器

        //绑定事件
        this.eventBind = function () {
            var self = this;

            $obj.bind('click', function () {
                self.load();
            });

            $("body").on('click', "#" + opts.mapid + " .mapsearch-popup-close", function () {
                $("#" + opts.mapid).hide();
                $("#" + opts.mapid).remove();
            });
            $("body").on('click', "#" + opts.mapid + " .mapsearch-popup-btnOk", function () {
                if (opts.bdlng > 0 && opts.address != "") {
                    var baidupoint = {lng:opts.bdlng,lat:opts.bdlat}
                    var point = bd09towgs84(opts.bdlng, opts.bdlat);
                    opts.lng = point[0];
                    opts.lat = point[1];
                    var wgs84point = { lng: opts.lng, lat: opts.lat }
                    $obj.val(opts.address);
                    $obj.attr("title", opts.address);
                    $("#" + opts.mapid).hide();
                    $("#" + opts.mapid).remove();
                    if (opts.dobackcall != null) {
                        opts.dobackcall(opts.address, wgs84point, baidupoint);
                    }
                }
                else {
                    alertMsg("请选择地点");
                }
            });


        };
        //加载地图
        this.load = function () {

            var html = '<div class="popup mapsearch-popup" id="' + opts.mapid + '" >';
            html = html + '<div class="popup-mask"></div>';
            html = html + '<div class="popup-box">';
            html = html + '<div class="popup-title">';
            html = html + '地图选点<i class="mapsearch-popup-close">×</i>';
            html = html + '</div>';
            html = html + '<div class="popup-content">';
            html = html + '<div class="content-map">';
            html = html + '<div class="search-box">';
            html = html + '<span>请输入关键字：</span>';
            html = html + '<input type="text" class="mapsearch-popup-key" id="' + opts.mapid + 'key" style="z-index:6000">';
            html = html + '</div>';
            html = html + '<div class="map-box" id="' + opts.mapid + 'map" style="background-color:#f0f0f0"></div>';

            html = html + '</div>';
            html = html + '<div class="content-item item-btn">';
            html = html + '<div class="btn-box">';
            html = html + '<button class="mapsearch-popup-btnOk">确定</button>';
            html = html + '<button class="mapsearch-popup-close">返回</button>';
            html = html + '</div>';
            html = html + '</div>';
            html = html + '</div>';
            html = html + '</div>';
            html = html + '</div>';
            $("body").append(html);

            init_page_bdmap(opts.mapid + "map");

            BMapLib.EventWrapper.addListener(map, 'load', function (e) {
                if (opts.lng != "" && opts.lat != "" && $obj.val() != "") {
                    var bdpoint = wgs84tobd09(Number(opts.lng), Number(opts.lat));
                    opts.lng = bdpoint[0];
                    opts.lat = bdpoint[1];
                    opts.address = $obj.val();

                    addMarker(opts.lng, opts.lat, opts.address);
                }
                else {

                    var geolocation = new BMap.Geolocation();
                    geolocation.getCurrentPosition(function (r) {
                        if (this.getStatus() == BMAP_STATUS_SUCCESS) {
                            addMarker(r.point.lng, r.point.lat);
                        }
                    }, { enableHighAccuracy: true })
                }
            });

            var ac = new BMap.Autocomplete(    //建立一个自动完成的对象
            {
                "input": opts.mapid + "key"
               , "location": map
            });
            ac.addEventListener("onconfirm", function (e) {    //鼠标点击下拉列表后的事件
                var _value = e.item.value;
                myValue = _value.province + _value.city + _value.district + _value.street + _value.business + "";
                var local = new BMap.LocalSearch(map, { //智能搜索
                    onSearchComplete: myFun
                });
                local.search(myValue);
                function myFun() {
                    var pp = local.getResults().getPoi(0).point;//获取第一个智能搜索的结果
                    addMarker(pp.lng, pp.lat);
                }
            });

            $(".mapsearch-popup").show();
        }

        //初始化
        this.init = function () {
            opts.mapid = "div" + newGuid() + "" + new Date().getTime();
            this.eventBind();
        };
        this.init();

        //根据地图控件名称，加载地图
        function init_page_bdmap(ele) {
            //设置地图
            map = new BMap.Map(ele, { enableMapClick: false });


            map.centerAndZoom(opts.city, 15);

            map.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放
        }

        //添加Marker
        function addMarker(lng, lat, address) {
            map.clearOverlays();
            var point = new BMap.Point(lng, lat);
            map.panTo(point);
            var marker = new BMap.Marker(point);// 创建标注
            if (address) {
                var label = new BMap.Label(address, { offset: new BMap.Size(-11 * address.length / 2, -30) });
                label.setStyle({ fontSize: '14px', padding: '5px', borderRadius: '5px' });
                marker.setLabel(label);
                map.addOverlay(marker);
                marker.enableDragging();

            }
            else {
                getBaiduAddressByLngLat(lng, lat, function (addr) {
                    var label = new BMap.Label(addr, { offset: new BMap.Size(-11 * addr.length / 2, -30) });
                    label.setStyle({ fontSize: '14px', padding: '5px', borderRadius: '5px' });
                    marker.setLabel(label);
                    map.addOverlay(marker);
                    marker.enableDragging();
                    setLngLat(lng, lat, addr);
                });

            }
            BMapLib.EventWrapper.addListener(marker, 'dragging', function (e) {
                var label = this.getLabel();
                label.setContent("......");
                label.setOffset(new BMap.Size(-8, -30));
                setLngLat(0, 0, "");

            });
            BMapLib.EventWrapper.addListener(marker, 'dragend', function (e) {
                var point = e.point;
                var marker = this;
                getBaiduAddressByLngLat(point.lng, point.lat, function (addr) {
                    var label = marker.getLabel();
                    label.setContent(addr);
                    label.setOffset(new BMap.Size(-11 * addr.length / 2, -30));
                    setLngLat(point.lng, point.lat, addr);
                    map.panTo(point);
                });
            });

        }
        //根据坐标获得详细地址
        function getBaiduAddressByLngLat(lng, lat, callback) {

            var geoc = new BMap.Geocoder();
            var point = new BMap.Point(lng, lat);
            geoc.getLocation(point, function (rs) {

                if (rs != null) {
                    var address = rs.addressComponents;

                    if (address != null && address.province != null && address.province != "") {
                        var points = rs.surroundingPois;
                        var title = "";
                        var dis;
                        if (points.length > 0) {
                            for (var i = 0; i < points.length; i++) {
                                var onepoint = points[i].point;
                                if (title == "") {
                                    title = points[i].title;
                                    dis = BMapLib.GeoUtils.getDistance(onepoint, rs.point);
                                }
                                else {
                                    var curdis = BMapLib.GeoUtils.getDistance(onepoint, rs.point);
                                    if (curdis < dis) {
                                        dis = curdis;
                                        title = points[i].title;
                                    }
                                }
                            }
                        }
                        var raddress = address.province + " " + address.city + " " + address.district + " " + address.street + " " + title + (dis > 0 ? dis.toFixed(2) + "米" : "") + "附近";
                        callback(raddress);
                    }
                    else {
                        callback("No Results Found");
                    }
                }
                else {
                    callback("No Results Found");
                }
            }, { poiRadius: 1000, numPois: 2 });
        }

        //设置经纬度
        function setLngLat(lng, lat, address) {
            opts.bdlng = lng;
            opts.bdlat = lat;
            opts.address = address;
        }
    };

    $.fn.baidumapaddress = function (parameter) {

        var options = $.extend({}, defaults, parameter);
        return this.each(function () {
            var baidumapaddress = new BaiduMapAddress(this, options);
        });
    };

})(jQuery, window, document);

function alertMsg(title) {
    alert(title);
}
//生成唯一页面标识
function newGuid() {
    var guid = "";
    for (var i = 1; i <= 32; i++) {
        var n = Math.floor(Math.random() * 16.0).toString(16);
        guid += n;
    }
    guid += new Date().getTime();
    return guid.toUpperCase();
}