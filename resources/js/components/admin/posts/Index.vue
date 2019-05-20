<template>
    <div class="col-md-12">
        <div class="card p-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h5 class="card-title float-left text-white">Posts</h5>
                            <app-post-create  :tags="tags" :categories="categories"></app-post-create>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Tag</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Attachment</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(post, index) in posts" :key="index">
                                        <td>{{ ++index }}</td>
                                        <td>{{ post.title }}</td>
                                        <td>{{ post.category.name }}</td>
                                        <td>{{ post.tag.name }}</td>
                                        <td>{{ post.description }}</td>
                                        <td><img :src="post.attachmentUrl" alt="" width="64px"></td>
                                        <td>
                                            <app-post-edit :tags="tags" :categories="categories" :post="post" @updated="getData()"></app-post-edit>
                                            <button @click.prevent="deleteData(post)" class="btn btn-sm btn-danger" type="button"><i class="fa fa-trash"></i></button>
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
import AppPostCreate from './Create.vue';
import AppPostEdit from './Edit.vue';

export default {
    components: {
        AppPostCreate,
        AppPostEdit
    },
    props:['categories', 'tags'],
    data() {
        return {
            posts: ''
        }
    },
    methods: {
        getData() {
            axios.get(`/admin/posts`)
            .then(res => {
                this.posts = res.data
            })
        },
        deleteData(post) {
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
                    axios.delete(`/admin/posts/${post.id}`)
                    .then(res => {
                        this.getData()
                        Swal.fire(
                            'Post deleted successfully',
                            '',
                            'success'
                        )
                    })
                }
            })
        }
    },
    created() {
        this.getData()
    }
}
</script>
