<template>
  <div class="home-page">
    <div class="container">
      <template v-if="amo_options">
        <div class="d-flex justify-content-between my-3">
        <div class="title">Сделка</div>
        <div class="d-flex flex-column">
           <div class="text-success">Амо подключено</div>
        <div class="text-danger pointer" @click="resetAmo">Отключить</div>
        </div>
        </div>
       
      <form class="form">
        <div class="mb-3">
          <label for="name" class="form-label">Имя</label>
          <input type="text" class="form-control" id="name" v-model="name" :class="error['name'] ? 'errorInput': ''" />
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" v-model="email" :class="error['email'] ? 'errorInput': ''" />
        </div>
        <div class="mb-3">
          <label for="phone" class="form-label">Телефон</label>
          <input type="text" class="form-control" id="phone" v-model="phone" :class="error['phone'] ? 'errorInput': ''" />
        </div>
        <div class="mb-3">
          <label for="price" class="form-label">Цена</label>
          <input type="text" class="form-control" id="price" v-model="price" :class="error['price'] ? 'errorInput': ''"/>
        </div>
        <template v-if="Object.keys(error).length !== 0">
          <div class="text-danger" v-for="(item, key) in error" :key="'error_'  + key">
            {{item}}
          </div>
        </template>
        <div v-if="success" class="text-success">Отправлено!</div>
        <button
          type="submit"
          class="btn btn-primary mt-3"
          @click.prevent="amoSendDeal"
          :disabled="loading"
        >
          {{loading ? "Загрузка":"Отправить"}}
        </button>
      </form>
      </template>
      <template v-else>
        <a
        class="amoBtn"
          :href="'https://www.amocrm.ru/oauth/?redirect_uri='+ redirect_url + '&approval_prompt=auto&response_type=code&scope=&mode=post_message&client_id=' + client_id"
          >Подключить Amo</a
        >
      </template>
      
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "home-page",
  data() {
    return {
      amo_options: null,
      error: {},
      name: null,
      email: null,
      phone: null,
      price: null,
      loading: false,
      success: false,
      // replace for different  params
      redirect_url : "https://sadof.pythonanywhere.com",
      client_id: "6ba47c52-1b45-4bb3-bed3-b12548bd854d"
    };
  },
  methods: {
    resetAmo() {
      localStorage.removeItem("amo_options");
      this.amo_options = null;
    },
    async amoSendDeal() {
      this.$set(this, "error", {});
      this.success = false;
      if (!this.name || !this.name.length) {
        this.$set(this.error, "name", "Заполните имя");
      }
      if (
        !this.email || 0
        // !this.email.match(
        //   /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        // )
      ) {
        this.$set(this.error, "email", "Заполните email");
      }
      if (!this.phone || this.phone.length != 11) {
        this.$set(this.error, "phone", "Телефон должен содержать 11 символов");
      }
      if (!this.price || !this.price.length) {
        this.$set(this.error, "price", "Заполните цену");
      }
      if (Object.keys(this.error).length === 0) {
        this.loading = true;
        try {
          let request = {
            "amo_options" : this.amo_options,
            "name" : this.name,
            "email" : this.email,
            "phone" : this.phone,
            "price" : this.price,
          };
          let response = await axios.post("/api/amoSendDeal.php", new URLSearchParams(request));
          localStorage.setItem("amo_options", JSON.stringify(response.data));
          this.success = true;
          setTimeout(() => {this.success = false}, 3000);
        } catch (err) {
          this.$set(this.error, "send" , err)
        }
        this.loading = false;
      }
    },
  },
  mounted() {
    this.amo_options = localStorage.getItem("amo_options");
  },
};
</script>