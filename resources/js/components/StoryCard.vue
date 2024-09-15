<template>
    <div class="card card--story mb-4" :class="{'card-single': single, 'card-index': !single}">
        <div class="card-body">
            <h5 class="card-title">
                <i class="fas fa-thumbtack fa-sm pr-1" v-if="story.is_pinned && !single"></i>
                <span>{{ __('Story') }}</span> &mdash; <a :href="story.url">{{ story.title }}</a>
            </h5>

            <div class="card-content">
                <viewer :initialValue="story.content"></viewer>
            </div>

            <p class="card-text mt-1" v-if="story.tags.length > 0">
                <a v-for="tag in story.tags" class="badge badge-secondary mr-1" :href="`/tag/${tag}`">{{ tag }}</a>
            </p>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <span><i class="fas fa-lock pr-2" v-if="story.is_private"></i>{{ story.date_formated }}</span>

            <div class="dropdown">
                <button class="btn btn-outline-dark btn-sm dropdown-toggle"
                        type="button"
                        id="dropdownMenuButton"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                        dusk="story-card-more"
                >
                    {{ __('More') }}
                </button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item"
                       :href="story.url"
                       dusk="story-card-permalink"
                    >
                        <i class="fas fa-link fa-fw mr-1"></i> {{ __('Permalink') }}
                    </a>

                    <h6 class="dropdown-header" v-if="story.editable">{{ __('Manage') }}</h6>

                    <a class="dropdown-item"
                       @click="$bus.$emit('share', story)"
                       v-if="story.editable"
                       dusk="story-card-temp-share"
                    >
                        <i class="fas fa-share-square fa-fw mr-1"></i> {{ __('Temp sharing') }}
                    </a>
                    <a class="dropdown-item"
                       :href="story.url_edit"
                       v-if="story.editable"
                       dusk="story-card-edit"
                    >
                        <i class="fas fa-pen-alt fa-fw mr-1"></i> {{ __('Edit') }}
                    </a>

                    <confirm class="dropdown-item"
                             :text="`<i class='fas fa-trash-alt fa-fw mr-1'></i> ${__('Delete')}`"
                             :text-confirm="`<i class='fas fa-check fa-fw mr-1'></i> ${__('Confirm')}`"
                             @confirmed="remove"
                             v-if="story.editable"
                             dusk="story-card-remove"
                    ></confirm>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        single: {
            type: Boolean,
            required: false,
            default: false,
        },
        story: {
            type: Object,
            required: true,
            default: () => {}
        },
    },

    methods: {
        remove() {
            axios.delete(this.story.url_delete)
                .then(response => {
                    this.$toasted.success(this.__("Deleted"));

                    if (this.single) {
                        window.location = '/';
                    } else {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.log(error);
                    this.$toasted.error(this.__("Can't delete"));
                })
        }
    }
}
</script>
