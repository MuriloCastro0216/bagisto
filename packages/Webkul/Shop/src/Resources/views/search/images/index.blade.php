<v-image-search>
    <button
        type="button"
        class="icon-camera flex items-center absolute top-[10px] ltr:right-[12px] rtl:left-[12px] pr-3 text-[22px]"
        aria-label="Search"
    >
    </button>
</v-image-search>

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest/dist/tf.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/tensorflow-models-mobilenet-patch@2.1.1/dist/mobilenet.min.js"></script>

    <script type="text/x-template" id="v-image-search-template">
        <div>
            <label
                class="icon-camera flex items-center absolute top-[10px] ltr:right-[12px] rtl:left-[12px] pr-3 text-[22px] cursor-pointer"
                aria-label="Search"
                for="v-image-search"
            >
            </label>

            <input
                type="file"
                class="hidden"
                ref="imageSearchInput"
                id="v-image-search"
                @change="analyzeImage()"
            />

            <img
                id="uploaded-image-url"
                class="hidden"
                :src="uploadedImageUrl"
                alt="uploaded image url"
                width="20"
                height="20"
            />
        </div>
    </script>

    <script type="module">
        app.component('v-image-search', {
            template: '#v-image-search-template',

            data() {
                return {
                    uploadedImageUrl: '',
                };
            },

            methods: {
                /**
                 * This method will analyze the image and load the sets on the bases of trained model.
                 * 
                 * @return void
                 */
                analyzeImage() {
                    let imageInput = this.$refs.imageSearchInput;

                    if (imageInput.files && imageInput.files[0]) {
                        if (imageInput.files[0].type.includes('image/')) {
                            if (imageInput.files[0].size <= 2000000) {
                                let formData = new FormData();

                                formData.append('image', imageInput.files[0]);

                                axios
                                    .post('{{ route('shop.search.upload') }}', formData, {
                                        headers: {
                                            'Content-Type': 'multipart/form-data'
                                        }
                                    })
                                    .then(response => {
                                        let net;

                                        let self = this;

                                        this.uploadedImageUrl = response.data;

                                        async function app() {
                                            let analysedResult = [];

                                            let queryString = '';

                                            net = await mobilenet.load();

                                            try {
                                                const result = await net.classify(document.getElementById('uploaded-image-url'));

                                                result.forEach(function(value) {
                                                    queryString = value.className.split(',');

                                                    if (queryString.length > 1) {
                                                        analysedResult = analysedResult.concat(queryString);
                                                    } else {
                                                        analysedResult.push(queryString[0]);
                                                    }
                                                });
                                            } catch (error) {
                                                this.$emitter.emit('add-flash', { type: 'error', message: 'Something went wrong, please try again later.'});
                                            }

                                            localStorage.searchedImageUrl = self.uploadedImageUrl;

                                            queryString = localStorage.searchedTerms = analysedResult.join('_');

                                            window.location.href = `${'{{ route('shop.search.index') }}'}?query=${queryString}&image-search=1`;
                                        }

                                        app();
                                    })
                                    .catch((error) => {
                                        this.$emitter.emit('add-flash', { type: 'error', message: 'Something went wrong, please try again later.'});
                                    });
                            } else {
                                imageInput.value = '';

                                this.$emitter.emit('add-flash', { type: 'error', message: 'Size Limit Error'});
                            }
                        } else {
                            imageInput.value = '';

                            alert('Only images (.jpeg, .jpg, .png, ..) are allowed.');
                        }
                    }
                }
            }
        })
    </script>
@endPushOnce