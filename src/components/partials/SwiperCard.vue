<template>
  <md-card
    md-with-hover
    @click.native="router.push(feature.properties.link_relative)"
    md-text-scrim
    v-bind:class="selected ? 'md-elevation-24-not': 'else'"
    class="swiper-card"
  >
    <md-card-media-cover md-text-scrim>
      <md-card-media md-ratio="16:9">
        <img v-if="feature.properties.thumbnail" :src="feature.properties.thumbnail" alt="Skyscraper">
      </md-card-media>

      <md-card-area>
        <md-card-header>
          <span class="md-title">{{ feature.properties.title }}</span>
          <span class="md-subheading">{{ feature.properties.full_name }}</span>
        </md-card-header>
      </md-card-area>
    </md-card-media-cover>
  </md-card>
</template>

<script>
import { mapGetters } from 'vuex'

// https://vuematerial.io/components/card/
export default {
  props: {
    feature: {
      type: Object
    },
    index: 0
  }, // ['title', 'href', 'slug', 'data'],
  data () {
    return {
      router: this.$router,
      selected: false
    }
  },
  computed: {
    ...mapGetters(['getSelected'])
  },
  methods: {
    selectionChanged (f) {}
  },
  components: {
    // router: this.router
  },
  updated () {
    // console.log("after");
  },
  watch: {}
}
</script>
<style>

.swiper-card .md-card-header {
  padding: 2% 4.5%;
}
.md-content p {
  margin: 0 0 20px 0;
}
.swiper-card .md-title {
   font-size: 1.6em;
  line-height: 1em;
  padding-bottom: 2px;
}
.swiper-card .md-subheading {
  font-size: 1.2em;
  line-height: 1.1em;
}

@media only screen and (max-width: 900px) {
  .swiper-card .md-title {
    font-size: 1.4em;
  }
  .swiper-card .md-subheading {
    font-size: 1.2em;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
}
</style>
