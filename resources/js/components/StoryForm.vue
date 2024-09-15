<template>
    <div class="card card--story">
        <div class="card-body">
            <div class="form-group">
                <label for="title">{{ __('Title') }}</label>
                <input type="text" class="form-control" :class="{'is-invalid': hasFormError('title')}" id="title" v-model="form.title" :disabled="loading">
                <span class="invalid-feedback" v-if="hasFormError('title')">{{ firstFormError('title') }}</span>
                <p class="text-muted"><small>{{ fullUrl }}</small></p>
            </div>

            <div class="form-group">
                <label for="content">{{ __('Content') }}</label>
                <editor id="content"
                        height="auto"
                        previewStyle="tab"
                        :options="editor"
                        @change="parseContent"
                        :initialValue="this.form.content"
                        theme="dark"
                        ref="toastuiEditor"
                ></editor>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_private" v-model="form.is_private" :disabled="loading">
                            <label class="custom-control-label" for="is_private" dusk="story-form-private">{{ __('Is private?') }}</label>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_pinned" v-model="form.is_pinned" :disabled="loading">
                            <label class="custom-control-label" for="is_pinned" dusk="story-form-pinned">{{ __('Is pinned?') }}</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>{{ __('Tags') }}</label>
                <tags v-model="form.tags"></tags>
            </div>
        </div>

        <div class="card-footer">
            <div class="btn-group">
                <button type="button"
                        class="btn btn-primary"
                        :class="{'btn-danger': hasChanged}"
                        @click.prevent="submit('edit')"
                        :disabled="loading"
                        dusk="story-form-save"
                >
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" v-if="loading"></span>
                    {{ __('Save') }}
                </button>

                <button type="button"
                        class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                        :class="{'btn-danger': hasChanged}"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                ></button>

                <div class="dropdown-menu">
                    <button type="button" class="dropdown-item" @click.prevent="submit('view')">{{ __('Save & View') }}</button>
                    <button type="button" class="dropdown-item" @click.prevent="submit('new')">{{ __('Save & New') }}</button>
                </div>
            </div>
            <a :href="story.url" class="btn btn-outline-primary" v-if="story">{{ __('View')}}</a>
        </div>
    </div>
</template>

<script>
let defaultStory = function () {
    return {
        title: '',
        slug: '',
        content: '',
        is_private: false,
        is_pinned: false,
        tags: [],
    };
};

import audit from "../mixins/audit";
import formErrors from "../mixins/formErrors";
import httpErrors from "../mixins/httpErrors";

export default {
    mixins: [
        audit,
        formErrors,
        httpErrors,
    ],

    props: {
        story: {
            type: Object,
            required: false,
            default: () => {}
        },
    },

    data() {
        return {
            form: defaultStory(),
            loading: false,
            editor: {
                minHeight: '300px',
                language: document.getElementsByTagName('html')[0].getAttribute('lang') || 'en',
                useCommandShortcut: true,
                useDefaultHTMLSanitizer: true,
                usageStatistics: false,
                hideModeSwitch: true,
                toolbarItems: [[
                    'heading',
                    'bold',
                    'italic',
                    'strike',
                    ],[
                    'hr',
                    'quote',
                    ],[
                    'ul',
                    'ol',
                    'task',
                    'indent',
                    'outdent',
                    ],[
                    'table',
                    'image',
                    'link',
                    ],[
                    'code',
                    'codeblock'
                ]]
            }
        }
    },

    mounted() {
        if (this.story) {
            this.form = this.story;
        }
        let editor = this.$refs.toastuiEditor;
        editor.invoke("setMarkdown",this.form.content);
        this.startAudit();
    },

    methods: {
        submit(then) {
            this.loading = true;
            axios.request({
                method: this.story ? 'PUT' : 'POST',
                url: this.story ? this.story.url_update : '/api/story',
                data: this.form
            }).then(response => {
                this.clearAudit();
                this.$toasted.success(this.__("Saved"));

                switch (then) {
                    case 'view':
                        window.location = response.data.post.url;
                        break;
                    case 'new':
                        window.location = '/story/create';
                        break;
                    case 'edit':
                        window.location = `/story/${response.data.post.postable_id}/edit`;
                        break;
                }
            }).catch(error => {
                this.loading = false;
                this.setFormError(error);
                this.setHttpError(error);
                this.toastHttpError(this.__("Can't save"));
            })
        },

        parseContent(){
            let html = this.$refs.toastuiEditor.invoke('getMarkdown');
            this.form.content = html;
        }
    },

    computed: {
        fullUrl: function() {
            return `${window.location.origin}/${this.form.slug}`;
        },
    },

    watch: {
        'form.title': function(value) {
            this.form.slug = value.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
        },
    }
}
</script>
