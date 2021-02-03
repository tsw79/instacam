<template>
  <div class="container">
    <button
      @click="followUser"
      v-text="buttonText"
      class="btn btn-sm btn-primary ml-2"
    ></button>
  </div>
</template>

<script>
  export default {
    props: {
      userId: null,
      follows: false
    },
    data: function () {
      return {
        status: this.follows,
      }
    },
    methods: {
      followUser() {
        axios.post('/follow/' + this.userId)
          .then(response => {
              this.status = ! this.status;
              console.log(response.data);
          })
          .catch(errors => {
              if (errors.response.status == 401) {
                  window.location = '/login';
              }
          });
      }
    },
    computed: {
      buttonText() {
        return (this.status) ? 'Unfollow' : 'Follow';
      }
    },
    mounted() {
      console.log('Component FollowButton mounted.')
    }
  }
</script>
