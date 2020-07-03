console.log('module js init');
var Edomains = {
    preloader: {
        el: document.getElementById('edPreloader'),
        show: function () {
            var self = this;
            self.el.classList.add('show');
        },
        hide: function () {
            var self = this;
            self.el.classList.remove('show');
        }
    },
    sendXhr: function (data, endCallback, startCallback, url) {
        var xhr = new XMLHttpRequest();
        var self = this;
        if (!url) url = document.location.href;
        var method = "GET";
        if (data) {
            method = "POST";
        }
        xhr.open(method, url, true);
        xhr.setRequestHeader('X-REQUESTED-WITH', 'XMLHttpRequest');
        xhr.onloadstart = function () {
            self.preloader.show();
            if (typeof startCallback === 'function') startCallback(this);
        };
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var json = JSON.parse(xhr.response);
                if (typeof endCallback === 'function' && endCallback) {
                    endCallback(json)
                }
                if (json.fn) {
                    self[json.fn](json);
                }
            }
        };
        xhr.onloadend = function () {
            self.preloader.hide();
        };
        xhr.send(data);
    },
    changeVarType: function () {
        console.log('change');
        var fd = new FormData();
        fd.append('formid','loadVarType');
        fd.append('gvid',globalVarType.parentElement.parentElement.querySelector('input[name="id"]').value);
        fd.append('type',globalVarType.value);
        Edomains.sendXhr(fd,function (json) {
            if(json.output){
                document.getElementById('globalVarTypeResult').innerHTML = json.output;
            }
        });
    }
}
var globalVarType = document.getElementById('globalVarType');
document.addEventListener('DOMContentLoaded',function () {
    var alerts = document.querySelectorAll('[data-confirm]');
    for(var i=0;i<alerts.length;i++){
        alerts[i].onclick = function (e) {
            return confirm(this.dataset.confirm)
        }
    }
    if(globalVarType){
        if(globalVarType.value !== ''){
            Edomains.changeVarType();
        }
        globalVarType.addEventListener('change',function (ev) {
            ev.preventDefault();
            Edomains.changeVarType();
        })
    }
    var gVarBlocks = document.querySelectorAll('[data-gvar-id]');
    if (gVarBlocks.length > 0){
        for (var i=0;i<gVarBlocks.length;i++){
            gVarBlocks[i].querySelector('form').onsubmit = function (e) {
                e.preventDefault();
                var data = new FormData(this);
                var form = this;
                Edomains.sendXhr(data,function (json) {
                    if(json.create > 0){
                        form.querySelector('[name="var_id"]').value = json.create;
                    }
                    if(json.delete > 0){
                        form.querySelector('[name="var_id"]').value = '';
                    }
                })
            }
        }
    }
})