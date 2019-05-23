<template>
    <span>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal" :data-target="'#exampleModal-'+post.id">
        <i class="fa fa-edit"></i>
        </button>

        <!-- Modal -->
        <div class="modal fade" :id="'exampleModal-'+post.id" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Post Update</h5>
                    </div>
                    <form @submit.prevent="updateData()">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Category</label>
                                <div class="col-sm-10">
                                    <select class="form-control" v-model="form.category" :class="{'is-invalid' : errors.category}">
                                        <option value="">Choose Category</option>
                                        <option :value="category.id" v-for="(category, index) in categories" :key="index">{{ category.name }}</option>
                                    </select>
                                    <span v-if="errors.category" class="invalid-feedback" role="alert">
                                        <strong>{{ errors.category[0] }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tag</label>
                                <div class="col-sm-10">
                                    <select class="form-control" v-model="form.tag" :class="{'is-invalid' : errors.tag}">
                                        <option value="">Choose Tag</option>
                                        <option :value="tag.id" v-for="(tag, index) in tags" :key="index">{{ tag.name }}</option>
                                    </select>
                                    <span v-if="errors.tag" class="invalid-feedback" role="alert">
                                        <strong>{{ errors.tag[0] }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Title</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" v-model="form.title" :class="{'is-invalid' : errors.title}">
                                    <span v-if="errors.title" class="invalid-feedback" role="alert">
                                        <strong>{{ errors.title[0] }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" v-model="form.description" :class="{'is-invalid' : errors.description}"></textarea>
                                    <span v-if="errors.description" class="invalid-feedback" role="alert">
                                        <strong>{{ errors.description[0] }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Old Attachment</label>
                                <div class="col-sm-10">
                                    <div class="col-md-6 p-0">
                                        <img :src="post.attachmentUrl" class="img-responsive img-thumbnail" width="50%">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Attachment</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input @change="onImageChange($event)" type="file" class="custom-file-input" id="inputGroupFile02" :class="{'is-invalid' : errors['attachment.file']}">
                                            <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                                        </div>
                                    </div>
                                    <small class="text-danger" v-if="errors['attachment.file']">
                                        <strong>{{ errors['attachment.file'][0] }}</strong>
                                    </small>
                                    <div class="col-md-6 p-0 mt-3" v-if="form.attachment.file">
                                        <img :src="form.attachment.file" class="img-responsive img-thumbnail" width="100%">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </span>
</template>

<script>
export default {
    props:['categories', 'tags', 'post'],
    data() {
        return {
            form: {
                id: this.post.id,
                category: this.post.category_id,
                tag: this.post.tag_id,
                title: this.post.title,
                description: this.post.description,
                attachment: {
                    type: '',
                    file: ''
                }
            },
            orgForm: {
                id: '',
                category: '',
                tag: '',
                title: '',
                description: '',
                attachment: {
                    type: '',
                    file: ''
                }
            },
            errors: []
        }
    },
    methods: {
        toggleModal() {
        this.modalShown = !this.modalShown;
    },
        updateData() {
            axios.put(`/posts/${this.post.id}`, this.form)
            .then(res => {
                this.formReset();
                $('#exampleModal-'+this.post.id).modal('hide');
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });

                Toast.fire({
                    type: 'success',
                    title: 'Post updated successfully'
                })

                this.$emit('updated');
            })
            .catch(errors => {
                this.errors = errors.response.data.errors
            })
        },
        onImageChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0]);
        },
        createImage(file) {
            let file_name = file.name;
            let type = file_name.split(".").pop()
            this.form.attachment.type = type;
            console.log(type)

            let reader = new FileReader();
            reader.onload = (e) => {
                this.form.attachment.file = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        formReset() {
            this.form = this.orgForm
            this.errors = []
        }
    }
}
</script>

