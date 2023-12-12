$.ajax({
    url: '/index.html',
    method: 'get',
    dataType: 'html',
    success: function(data){
    text = data;
    }
    });
    
    alert(text);