$('#file-input').change(function() {
    if ($('#file-input').val() != '') {
        $(".upload-image").addClass("active");
        $(".upload-image-button").addClass("active");
        $("#reset-image").addClass("active");
    }
});

$("#reset-image").click(function() {
    const file = document.querySelector('#file-input');
    file.value = '';
    $(".upload-image").removeClass("active");
    $(".upload-image-button").removeClass("active");
    $("#reset-image").removeClass("active");
});

new Vue({
    el: '#app',
    data: {
        menu: 0,
        songs: [],
        url: 'libs/scroll',
        id: 5,
        page: 1,
        count: 10,
        loading: false,
        lastElement: true
    },
    methods: {
        onScroll: function(event) {
            var wrapper = event.target,
                list = wrapper.firstElementChild
            console.log('1')
            var scrollTop = wrapper.scrollTop,
                wrapperHeight = wrapper.offsetHeight,
                listHeight = list.offsetHeight

            var diffHeight = listHeight - wrapperHeight

            console.log(diffHeight)
            if (diffHeight <= scrollTop && !this.loading) {
                this.load()
                this.page++
            }
        },
        load: function() {
            this.loading = true
            this.$http.get(this.url, {params: {id: this.id, page: this.page, count: this.count}}).then(function(response) {

                var json = response.data,
                    songs = json

                this.songs = this.songs.concat(songs)
                this.loading = false
                if (songs.length < 10) this.lastElement = false;

            }, function(error) {
                //console.log(error)
                this.loading = false
            })
        }
    },
    created: function() {
        this.load()
        this.page++
    }
})