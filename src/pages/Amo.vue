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
        let response = await axios.post(
          `/api/addAmoIntegration.php`,
          new URLSearchParams({
            amo_code: this.code,
          }),
          {
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
          }
        );
        localStorage.setItem("amo_options", JSON.stringify(response.data));

        window.location.href = document.location.origin;
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