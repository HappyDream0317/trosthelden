<template>
    <div>
    <heading class="mb-6">Matching</heading>

    <h3>Parameter</h3>
    <div class="inline-flex justify-center" >
      <div class="inline-flex justify-center rounded-lg text-sm font-semibold py-3 px-4 me-4" v-bind:class="{ 'bg-primary-900' : debug,  'bg-primary-300' : !debug }" v-on:click="debug = !debug">
        debug
      </div>
      <div class="inline-flex justify-center rounded-lg text-sm font-semibold py-3 px-4" v-bind:class="{ 'bg-primary-900' : write, 'bg-primary-300' : !write }" v-on:click="write = !write">
        write
      </div>
    </div>
    <div class="spacer"></div>
    <h3>Match All</h3>
    <div class="inline-flex justify-center" >
      <button type="button" class="inline-flex justify-center rounded-lg text-sm font-semibold py-3 px-4 bg-primary-900 text-white hover:bg-primary-700 me-4" v-on:click="downloadCache">Download the cache</button>
      <button type="button" class="inline-flex justify-center rounded-lg text-sm font-semibold py-3 px-4 bg-primary-900 text-white hover:bg-primary-700" :disabled="loadingAll" v-on:click="onMatchAll">Rebuild cache for all matches</button>
    </div>
    <div class="spacer"></div>
    <svg v-if="loadingAll"
         class="spin fill-80 mb-6"
         height="35"
         viewBox="0 0 23 24"
         width="35"
         xmlns="http://www.w3.org/2000/svg"
    >
      <path
          d="M20.12 20.455A12.184 12.184 0 0 1 11.5 24a12.18 12.18 0 0 1-9.333-4.319c4.772 3.933 11.88 3.687 16.36-.738a7.571 7.571 0 0 0 0-10.8c-3.018-2.982-7.912-2.982-10.931 0a3.245 3.245 0 0 0 0 4.628 3.342 3.342 0 0 0 4.685 0 1.114 1.114 0 0 1 1.561 0 1.082 1.082 0 0 1 0 1.543 5.57 5.57 0 0 1-7.808 0 5.408 5.408 0 0 1 0-7.714c3.881-3.834 10.174-3.834 14.055 0a9.734 9.734 0 0 1 .03 13.855zM4.472 5.057a7.571 7.571 0 0 0 0 10.8c3.018 2.982 7.912 2.982 10.931 0a3.245 3.245 0 0 0 0-4.628 3.342 3.342 0 0 0-4.685 0 1.114 1.114 0 0 1-1.561 0 1.082 1.082 0 0 1 0-1.543 5.57 5.57 0 0 1 7.808 0 5.408 5.408 0 0 1 0 7.714c-3.881 3.834-10.174 3.834-14.055 0a9.734 9.734 0 0 1-.015-13.87C5.096 1.35 8.138 0 11.5 0c3.75 0 7.105 1.68 9.333 4.319C16.06.386 8.953.632 4.473 5.057z"
          fill-rule="evenodd"
      />
    </svg>
    <div class="spacer"></div>
    <template v-if="typeof loadingAll === 'string'"><b>{{ loadingAll }}</b></template>
    <div class="spacer"></div>
    <h3>Match Specific User</h3>
    <div class="inline-flex justify-center" >
      <input v-model="userId" class="appearance-none form-control form-input shadow me-4 py-3 px-4" style="margin-right: 30px;"
             type="number"/>
      <button type="button" class="inline-flex justify-center rounded-lg text-sm font-semibold py-3 px-4 bg-primary-900 text-white hover:bg-primary-700" :disabled="loadingOne" v-on:click="onMatchUser">Match User</button>
    </div>
      <svg v-if="loadingOne"
         class="spin fill-80 mb-6"
         height="35"
         viewBox="0 0 23 24"
         width="35"
         xmlns="http://www.w3.org/2000/svg"
    >
      <path
          d="M20.12 20.455A12.184 12.184 0 0 1 11.5 24a12.18 12.18 0 0 1-9.333-4.319c4.772 3.933 11.88 3.687 16.36-.738a7.571 7.571 0 0 0 0-10.8c-3.018-2.982-7.912-2.982-10.931 0a3.245 3.245 0 0 0 0 4.628 3.342 3.342 0 0 0 4.685 0 1.114 1.114 0 0 1 1.561 0 1.082 1.082 0 0 1 0 1.543 5.57 5.57 0 0 1-7.808 0 5.408 5.408 0 0 1 0-7.714c3.881-3.834 10.174-3.834 14.055 0a9.734 9.734 0 0 1 .03 13.855zM4.472 5.057a7.571 7.571 0 0 0 0 10.8c3.018 2.982 7.912 2.982 10.931 0a3.245 3.245 0 0 0 0-4.628 3.342 3.342 0 0 0-4.685 0 1.114 1.114 0 0 1-1.561 0 1.082 1.082 0 0 1 0-1.543 5.57 5.57 0 0 1 7.808 0 5.408 5.408 0 0 1 0 7.714c-3.881 3.834-10.174 3.834-14.055 0a9.734 9.734 0 0 1-.015-13.87C5.096 1.35 8.138 0 11.5 0c3.75 0 7.105 1.68 9.333 4.319C16.06.386 8.953.632 4.473 5.057z"
          fill-rule="evenodd"
      />
    </svg>
    <template v-if="typeof loadingOne === 'string'">{{ loadingOne }}</template>
    <p v-if="errorMsg" style="margin-top: 4px;">{{ errorMsg }}</p>
    <div class="spacer"></div>

    <div v-if="matchingHtml" class="inline-flex justify-center rounded-lg text-sm font-semibold py-3 px-4 bg-primary-900 text-white hover:bg-primary-700" style="margin-right: 30px;"
         v-on:click="matchingHtml = ''">clear
    </div>
    <div v-if="matchingHtml" class="spacer"></div>

    <div v-if="matchingHtml" v-html="matchingHtml"></div>

  </div>
</template>

<script>

export default {
  data: function () {
    return {
      debug: false,
      write: false,
      userId: 0,
      matchingHtml: '',
      errorMsg: '',
      loadingOne: false,
      loadingAll: false,
      answersExporting: false,
      messagesExporting: false,
      usersExporting: false,
    }
  },
  mounted() {
    this.refreshCacheStatus();
  },
  methods: {
    onMatchAll() {
      this.loadingAll = true;
      this.userId = null
      this.fetch()
    },
    onMatchUser() {
      this.errorMsg = '';
      this.loadingOne = true;

      if (this.userId) {
        this.fetch(this.userId)
      } else {
        this.errorMsg = 'user id missing'
      }
    },
    fetch() {
      this.matchingHtml = ''
      const data = this.userId ? {uid: this.userId} : {}
      Nova.request({
        method: "POST",
        url: "/nova-vendor/matching",
        data: {
          ...data,
          debug: this.debug,
          write: this.write,
        }
      }).then(({data}) => {
        if (data.status && data.status === "job dispatched" || data.status === "Job is already running.") {
          this.refreshCacheStatus();
        } else {
          this.loadingOne = false;
          this.matchingHtml = data.html;
        }
      }).catch((err) => {
          console.error(err);
        })
    },
    downloadCache() {
      var protocol = location.protocol;
      var slashes = protocol.concat("//");
      var host = slashes.concat(window.location.hostname);
      window.open(`${host}/nova-vendor/matching/current`)
    },
    refreshCacheStatus() {
      Nova.request({
        method: "GET",
        timeout: 300000 * 2, // 10 minutes
        url: "/nova-vendor/matching/current/status",

      }).then(({data}) => {
        if (data.status && data.status === "processing") {
          this.loadingAll = "Matches werden momentan noch berechnet... Diese Seite wird automatisch aktualisiert.";
          setTimeout(this.refreshCacheStatus, 5000)
        } else {
          window.alert("Cache updated! Ready for HTML download.")
          this.loadingAll = false;
        }
      }).catch((err) => {
          console.error(err);
      })
    }
  }
}
</script>

<style>
h3 {
  margin-bottom: 10px;
}

.spacer {
  margin-bottom: 30px;
}

.w-search {
  width: 150px;
}
</style>
