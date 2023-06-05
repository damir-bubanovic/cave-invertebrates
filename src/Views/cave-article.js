const { createApp } = Vue;

createApp({
  data() {
    return {
      search: '',
      articles: [],
    }
  },
  mounted() {
    this.indexArticle();
  },
  computed: {
    filteredArticles() {
      return this.articles.filter((article) => {
        return article.search.toLowerCase().match(this.search.toLowerCase());
      });
    }
  },
  methods: {
    indexArticle() {
      var self = this;
      var params = new URLSearchParams();
      params.append('action', 'article_list');

      axios.post('/wp-admin/admin-ajax.php', params)
        .then(function (response) {
          self.articles = response.data;
        });
    },
    fullReference(reference) {
      Swal.fire({
        title: reference,
        width: '50%'
      });
    },
  },
}).mount('#cave-inv-articles')
