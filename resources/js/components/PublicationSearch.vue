<template>
    <div class="container">
        <div class="row" v-if="errorMessage">
            <div class="col-12 mb2">
                <div class="alert alert-danger" role="alert">{{ errorMessage }}</div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="form-group mb-0">
                    <label for="doi">Document Object Identifier</label>
                    <input type="text" placehoder="Enter a full or partian DOI here" id="doi" v-model="doi" class="form-control" @keydown.enter="getPublication" autofocus>
                </div>
            </div>
            <div class="col-4 d-flex align-items-end">
                <button type="button" class="btn btn-primary w-100" :disabled="!canSubmit" @click="getPublication">
                    <span v-if="submitting"><span class="spinner-grow spinner-grow-sm mr-2" role="status"></span>Please wait</span>
                    <span v-else>Search</span>
                </button>
            </div>
        </div>
        <div class="row" v-if="resultsAvailable">
            <div class="col-12 pt-5">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Digital Object ID</th>
                            <th>Title</th>
                            <th>Publisher</th>
                            <th>Url</th>
                            <th>Retrieved from Provider</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="publication in publications" :key="publication.doi">
                            <td>{{ publication.doi }}</td>
                            <td>{{ publication.title }}</td>
                            <td>{{ publication.publisher }}</td>
                            <td><a :href="publication.url" v-if="publication.url" target="_blank">{{ publication.url }}</a></td>
                            <td>{{ publication.retrieved_at }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'PublicationSearch',
    data() {
        return {
            publications: [],
            doi: '',
            errorMessage: null,
            submitting: false,
        }
    },
    computed: {
        canSubmit() {
            return !this.submitting && this.doi.length;
        },
        resultsAvailable() {
            return this.publications.length;
        },
    },
    methods: {
        /**
         * Clears error and success messages
         */
        clearMessages() {
            this.errorMessage = null;
        },
        /**
         * Posts the form to the API and returns the calculated fee
         */
        getPublication() {
            if (this.submitting) {
                return
            }
            this.submitting = true;
            this.publications = [];
            this.clearMessages();
            fetch(`/api/publication?doi=${escape(this.doi)}`, {
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                this.submitting = false;
                this.clearMessages();
                if (data.status === 'success') {
                    this.publications = data.publications;
                } else {
                    this.errorMessage = data.message ? data.message : 'An error ocurred while processing your request';
                }
            }).catch(error => {
                this.submitting = false;
                this.errorMessage = error.message;
            })
        }
    }
}
</script>
