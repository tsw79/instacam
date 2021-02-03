<template>
  <div>
    <div class="dropdown">
      <input v-model="query" type="text" placeholder="Search" class="border rounded">
      <div
          v-if="results.length > 0 && query"
          class="dropdown-content card"
      >
        <div class="card-body pb-0 pt-2">
          <p
            v-for="result in results.slice(0,10)"
            :key="result.id"
            class="my-1"
          >
            <a :href="result.url">
            <div v-text="result.title"></div>
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        query: null,
        results: []
      };
    },
    watch: {
      query(after, before) {
        this.searchMembers();
      }
    },
    methods: {
      searchMembers() {
        axios.get('/search', { params: { query: this.query } })
          .then((response) => {
            this.results = response.data
          })
          .catch((err) => {
            console.log(err);
          });
      }
    }
  }
</script>

<style scoped>
  .dropdown {
    position: relative;
    display: inline-block;
  }
  .dropdown-content {
    position: absolute;
    /* background-color: #f1f1f1; */
    min-width: 200px;
    overflow: auto;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
  }
</style>
