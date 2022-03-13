<template>
  <div>
    <div class="container">
      <div class="w-50 mr-auto ml-auto justify-content-center">
        <div class="row text-center">
          <div class="col-12">
            <div v-if="error">{{ error }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Amo-page",
  data() {
    return {
      code: null,
      error: null,
    };
  },
  methods: {
    async addIntegration() {
      try {
        let response = await axios.post(`/api/addAmoIntegration.php`, {
          amo_code: this.code,
        });
        console.log(response);
        // localStorage.setItem("amo_options", JSON.stringify(response.data));
        //         var xhr = new XMLHttpRequest();
        // xhr.open("POST", 'http://127.0.0.1:8000/app/addAmoIntegration.php');
        //   xhr.setRequestHeader("Content-Type", "application/json");

        // let data =JSON.stringify({amo_token: this.amo_token});

        // xhr.send(data);
        // var data = {
        //   amo_token: this.amo_token,
        // };
        // var url = "http://localhost:8000/api/addAmoIntegration.php";
        // const response = await fetch(url, {
        //   method: "POST", // *GET, POST, PUT, DELETE, etc.
        //   mode: "cors", // no-cors, *cors, same-origin
        //   cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        //   credentials: "same-origin", // include, *same-origin, omit
        //   headers: {
        //     "Content-Type": "application/json",
        //     // 'Content-Type': 'application/x-www-form-urlencoded',
        //   },
        //   redirect: "follow", // manual, *follow, error
        //   referrerPolicy: "no-referrer", // no-referrer, *client
        //   body: JSON.stringify(data), // body data type must match "Content-Type" header
        // });
        // console.log(response);
        // // window.location.href = document.location.origin;
      } catch (err) {
        console.log(err);
        this.error = "Ошибка соединения с Amo";
      }
    },
  },
  async mounted() {
    const url = new URL(location.href);
    this.code = url.searchParams.get("code");
    if (this.code) {
      this.addIntegration();
    } else {
      this.error = "Ошибка получения токена";
    }
  },
};
</script>