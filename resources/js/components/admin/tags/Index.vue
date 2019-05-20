<template>
    <div class="col-md-12">
        <div class="card p-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title">Tag {{ editable ? 'Update' : 'Create' }}</h5>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="editable ? updateData() : storeData()">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tag Name</label>
                                    <input class="form-control" :class="{'is-invalid':errors.name}" v-model="form.name" placeholder="Enter name">
                                    <small id="emailHelp" class="form-text text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
                                </div>
                                <button type="button" @click.prevent="resetForm()" class="btn btn-sm btn-danger float-left">Reset</button>
                                <button type="submit" class="btn btn-sm btn-primary float-right">{{ editable ? 'Update' : 'Submit' }}</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title float-left">Tags</h5>
                            <button @click.prevent="deleteAllData()" class="btn btn-sm btn-danger float-right" type="button">Delete All</button>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(tag, index) in tags" :key="index">
                                        <th scope="row">{{ ++index }}</th>
                                        <td>{{ tag.name }}</td>
                                        <td>
                                            <button @click.prevent="editData(tag)" type="button" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></button>
                                            <button @click.prevent="deleteData(tag, --index)" type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            errors: [],
            tags: [],
            form: {
                id: '',
                name: ''
            },
            nullForm: {
                id: '',
                name: ''
            },
            editable: false,
        }
    },
    methods: {
        storeData() {
            axios.post(`/admin/tags`, this.form)
            .then(res => {
                this.resetForm();
                this.getData()
                Swal.fire(
                    'Tag created',
                    '',
                    'success'
                )
            })
            .catch(err => {
                this.errors = err.response.data.errors
            })
        },
        getData() {
            axios.get(`/admin/tags`)
            .then(res => {
                this.tags = res.data
            })
        },
        editData(tag) {
            this.editable = true;
            this.form.id = tag.id
            this.form.name = tag.name
        },
        updateData() {
            axios.put(`/admin/tags/${this.form.id}`, this.form)
            .then(res => {
                this.resetForm();
                this.getData()
                Swal.fire(
                    'Tag updated',
                    '',
                    'success'
                )
            })
            .catch(err => {
                this.errors = err.response.data.errors
            })
        },
        deleteData(tag, index) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.value) {
                    axios.delete(`/admin/tags/${tag.id}`)
                    .then(res => {
                        this.tags.splice(index, 1)
                        Swal.fire(
                            'Tag deleted',
                            '',
                            'success'
                        )
                    })
                }
            })
        },
        deleteAllData() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.value) {
                    axios.delete(`/admin/all-tag-delete`)
                    .then(res => {
                        this.tags = []
                        Swal.fire(
                            'All tag deleted',
                            '',
                            'success'
                        )
                    })
                }
            })
        },
        resetForm() {
            this.editable = false
            this.form = this.nullForm
        }
    },
    created() {
        this.getData()
    }
}
</script>
