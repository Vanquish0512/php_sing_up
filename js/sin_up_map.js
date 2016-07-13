var geocoder;
var map;
var myLatlng = new google.maps.LatLng(35.6894875,139.6917064); // 座標の初期値
var marker;
var marker2;
var ewerd;
// 初期化処理
function initialize() {

    geocoder = new google.maps.Geocoder();

    var myOptions = {
        zoom: 8,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }

    // 地図を生成する
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

    // マーカーを追加
    marker = new google.maps.Marker({
        position: myLatlng,
        map: map, 
        draggable: true // ドラッグ可能にする
    });
	
    // ドラッグが終了した時の処理
    google.maps.event.addListener(marker, "dragend", function() {
        setLatLng(marker);
        map.setCenter(marker.getPosition());
    });

    setLatLng(marker); // 座標書き出し

}




// 住所から検索
function codeAddress() {
    var address = document.getElementById("address").value;
    geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            marker.setPosition(results[0].geometry.location);
            setLatLng(marker); // 座標書き出し
        } else {
            alert("Geocode was not successful for the following reason: " + status);
        }
    });
}

// マーカーの位置をテキストフィールドに書きだす。
function setLatLng(marker) {
    p = marker.getPosition();
    p_lat = document.getElementById('latitude').value  = p.lat();
    document.getElementById('longitude').value = p.lng();
	
}
