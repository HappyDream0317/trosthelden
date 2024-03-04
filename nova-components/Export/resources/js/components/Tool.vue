<template>
    <div>
        <div style="margin-bottom: 6rem;">
            <div style="display: flex;">
                <heading class="mb-6">User Answer Export</heading>
                <Loader v-if="answersExporting" style="margin-left: 1rem;margin-top: -.3rem;" />
            </div>
            <button type="button" class="inline-flex justify-center rounded-lg text-sm font-semibold py-3 px-4 bg-primary-900 text-white hover:bg-primary-700" v-on:click="downloadAnswerExport" :disabled="answersExporting">Download Export</button>
        </div>

        <div style="margin-bottom: 6rem;">
            <div style="display: flex;">
                <heading class="mb-6">User Message Export</heading>
                <Loader v-if="MessageExporting" style="margin-left: 1rem;margin-top: -.3rem;" />
            </div>
            <button type="button" class="inline-flex justify-center rounded-lg text-sm font-semibold py-3 px-4 bg-primary-900 text-white hover:bg-primary-700" v-on:click="downloadMesssageExport" :disabled="MessageExporting">Download Export</button>
        </div>

        <div style="margin-bottom: 6rem;">
            <div style="display: flex;">
                <heading class="mb-6">User Export</heading>
                <Loader v-if="usersExporting" style="margin-left: 1rem;margin-top: -.3rem;" />
            </div>
            <button type="button" class="inline-flex justify-center rounded-lg text-sm font-semibold py-3 px-4 bg-primary-900 text-white hover:bg-primary-700" v-on:click="downloadUserExport" :disabled="usersExporting">Download Export</button>
        </div>

        <div style="margin-bottom: 6rem;">
            <div style="display: flex;">
                <heading class="mb-6">User Frabo Status</heading>
                <Loader v-if="usersFraboStatusExporting" style="margin-left: 1rem;margin-top: -.3rem;" />
            </div>
            <button type="button" class="inline-flex justify-center rounded-lg text-sm font-semibold py-3 px-4 bg-primary-900 text-white hover:bg-primary-700" v-on:click="downloadUsersFraboStatusExport" :disabled="usersFraboStatusExporting">Download Export</button>
        </div>

        <div style="margin-bottom: 6rem;">
            <div style="display: flex;">
                <heading class="mb-6">User Insights</heading>
                <Loader v-if="usersInsightsExporting" style="margin-left: 1rem;margin-top: -.3rem;" />
            </div>
            <button type="button" class="inline-flex justify-center rounded-lg text-sm font-semibold py-3 px-4 bg-primary-900 text-white hover:bg-primary-700" v-on:click="downloadUserInsightsExport" :disabled="usersInsightsExporting">Download Export</button>
        </div>

        <div style="margin-bottom: 6rem;">
            <div style="display: flex;">
                <heading class="mb-6">User Matches per Cluster</heading>
                <Loader v-if="usersClusterRankingExporting" style="margin-left: 1rem;margin-top: -.3rem;" />
            </div>
            <button type="button" class="inline-flex justify-center rounded-lg text-sm font-semibold py-3 px-4 bg-primary-900 text-white hover:bg-primary-700" v-on:click="downloadUserClusterRankingExport" :disabled="usersClusterRankingExporting">Download Export</button>
        </div>

        <div style="margin-bottom: 6rem;">
            <div style="display: flex;">
                <heading class="mb-6">Still Open Friend Requests</heading>
                <Loader v-if="stillOpenFriendRequestsExporting" style="margin-left: 1rem;margin-top: -.3rem;" />
            </div>
            <button type="button" class="inline-flex justify-center rounded-lg text-sm font-semibold py-3 px-4 bg-primary-900 text-white hover:bg-primary-700" v-on:click="downloadStillOpenFriendRequestsExport" :disabled="stillOpenFriendRequestsExporting">Download Export</button>
        </div>

        <div style="margin-bottom: 6rem;">
            <div style="display: flex;">
                <heading class="mb-6">User Notifications</heading>
                <Loader v-if="userNotificationsExporting" style="margin-left: 1rem;margin-top: -.3rem;" />
            </div>
            <button type="button" class="inline-flex justify-center rounded-lg text-sm font-semibold py-3 px-4 bg-primary-900 text-white hover:bg-primary-700" v-on:click="downloadUserNotificationsExport" :disabled="userNotificationsExporting">Download Export</button>
        </div>

        <div style="margin-bottom: 6rem;">
              <div style="display: flex;">
                  <heading class="mb-6">User Status and Referrer</heading>
                  <Loader v-if="userStatusReferrerExporting" style="margin-left: 1rem;margin-top: -.3rem;" />
              </div>
              <button type="button" class="inline-flex justify-center rounded-lg text-sm font-semibold py-3 px-4 bg-primary-900 text-white hover:bg-primary-700" v-on:click="downloadUserStatusReferrerExport" :disabled="userStatusReferrerExporting">Download Export</button>
          </div>
        </div>
</template>

<script>

import Loader from "./Loader";

  export default {
    components: {
      Loader
    },
    data: function () {
      return {
        errorMsg: '',
        loadingOne: false,
        loadingAll: false,
        answersExporting: false,
        messagesExporting: false,
        usersExporting: false,
        usersFraboStatusExporting: false,
        usersInsightsExporting: false,
        usersClusterRankingExporting: false,
        stillOpenFriendRequestsExporting: false,
        userNotificationsExporting: false,
        userStatusReferrerExporting: false,
      }
    },
    mounted() {
    },
    methods: {
      downloadAnswerExport(){
        this.answersExporting = true;
        Nova.request({
          method: "POST",
          url: "/nova-vendor/export/answer-export",
          responseType: 'blob',
          headers: {
            'Content-Disposition': "attachment; filename=template.xlsx",
            'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
          }
        }).then(({data}) => {
          this.downloadBlob(data, 'sheet.xls')
        }).finally(() => {
          this.answersExporting = false;
        }).catch((err) => {
          console.error(err);
        })
      },
      downloadMesssageExport(){
        this.messagesExporting = true;
        Nova.request({
          method: "POST",
          url: "/nova-vendor/export/message-export",
          responseType: 'blob',
          headers: {
            'Content-Disposition': "attachment; filename=template.xlsx",
            'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
          }
        }).then(({data}) => {
          this.downloadBlob(data, 'messages.xls')
        }).finally(() => {
          this.messagesExporting = false;
        }).catch((err) => {
          console.error(err);
        })
      },
      downloadUserExport(){
        this.usersExporting = true;
        Nova.request({
          method: "POST",
          url: "/nova-vendor/export/users",
          responseType: 'blob',
          headers: {
            'Content-Disposition': "attachment; filename=users.xlsx",
            'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
          }
        }).then(({data}) => {
          this.downloadBlob(data, 'users.xls')
        }).finally(() => {
          this.usersExporting = false;
        }).catch((err) => {
          console.error(err);
        })
      },
      downloadUserInsightsExport(){
        this.usersInsightsExporting = true;
        Nova.request({
          method: "POST",
          url: "/nova-vendor/export/user-insights",
          responseType: 'blob',
          headers: {
            'Content-Disposition': "attachment; filename=user-insights.xlsx",
            'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
          }
        }).then(({data}) => {
          this.downloadBlob(data, 'user-insights.xls')
        }).finally(() => {
          this.usersInsightsExporting = false;
        }).catch((err) => {
          console.error(err);
        })
      },
      downloadUserClusterRankingExport(){
        this.usersClusterRankingExporting = true;
        Nova.request({
          method: "POST",
          url: "/nova-vendor/export/user-cluster-ranking",
          responseType: 'blob',
          headers: {
            'Content-Disposition': "attachment; filename=user-cluster-rankings.xlsx",
            'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
          }
        }).then(({data}) => {
          this.downloadBlob(data, 'user-cluster-rankings.xls')
        }).finally(() => {
          this.usersClusterRankingExporting = false;
        }).catch((err) => {
          console.error(err);
        })
      },
      downloadUsersFraboStatusExport(){
        this.usersFraboStatusExporting = true;
        Nova.request({
          method: "POST",
          url: "/nova-vendor/export/users-frabo-status",
          responseType: 'blob',
          headers: {
            'Content-Disposition': "attachment; filename=users-frabo-status.xlsx",
            'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
          }
        }).then(({data}) => {
          this.downloadBlob(data, 'users-frabo-status.xls')
        }).finally(() => {
          this.usersFraboStatusExporting = false;
        }).catch((err) => {
          console.error(err);
        })
      },
      downloadStillOpenFriendRequestsExport(){
        this.stillOpenFriendRequestsExporting = true;
        Nova.request({
          method: "POST",
          url: "/nova-vendor/export/still-open-friend-requests",
          responseType: 'blob',
          headers: {
            'Content-Disposition': "attachment; filename=still-open-friend-requests.xlsx",
            'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
          }
        }).then(({data}) => {
          this.downloadBlob(data, 'still-open-friend-requests.xls')
        }).finally(() => {
          this.stillOpenFriendRequestsExporting = false;
        }).catch((err) => {
          console.error(err);
        })
      },
      downloadUserNotificationsExport(){
        this.userNotificationsExporting = true;
        Nova.request({
          method: "POST",
          url: "/nova-vendor/export/user-notifications",
          responseType: 'blob',
          headers: {
            'Content-Disposition': "attachment; filename=user-notifications.xlsx",
            'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
          }
        }).then(({data}) => {
          this.downloadBlob(data, 'user-notifications.xls')
        }).finally(() => {
          this.userNotificationsExporting = false;
        }).catch((err) => {
          console.error(err);
        })
      },
      downloadUserStatusReferrerExport(){
        this.userStatusReferrerExporting = true;
        Nova.request({
          method: "POST",
          url: "/nova-vendor/export/user-status-referrer",
          responseType: 'blob',
          headers: {
            'Content-Disposition': "attachment; filename=user-status-referrer.xlsx",
            'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
          }
        }).then(({data}) => {
          this.downloadBlob(data, 'user-status-referrer.xlsx')
        }).finally(() => {
          this.userStatusReferrerExporting = false;
        }).catch((err) => {
          console.error(err);
        })
      },
      downloadBlob(blob, name = 'file.txt') {
        // Convert your blob into a Blob URL (a special url that points to an object in the browser's memory)
        const blobUrl = URL.createObjectURL(blob);
        // Create a link element
        const link = document.createElement("a");
        // Set link's href to point to the Blob URL
        link.href = blobUrl;
        link.download = name;
        // Append link to the body
        document.body.appendChild(link);
        // Dispatch click event on the link
        // This is necessary as link.click() does not work on the latest firefox
        link.dispatchEvent(
          new MouseEvent('click', {
            bubbles: true,
            cancelable: true,
            view: window
          })
        );
        // Remove link from body
        document.body.removeChild(link);
      },
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
