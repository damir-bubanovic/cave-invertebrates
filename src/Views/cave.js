const { createApp } = Vue
const { createVuetify } = Vuetify

const vuetify = createVuetify()

const app = createApp({
  data() {
    return {
        tab: '1',

        alert: {
          show: false,
          status: '',
          message: ''
        },

        table: [
          {
            no: 1,
            reference: 'Akos Fejér and Oana Moldovan, “Population Size and Dispersal Patterns for a Drimeotus (Coleoptera, Leiodidae, Leptodirini) Cave Population,” Subterranean Biology 11 (December 7, 2013): 31–44, https://doi.org/10.3897/subtbiol.11.4974.',
            authors: 'Akos Fejér |  Oana Moldovan',
            year: 2013,
            title: 'Population Size and Dispersal Patterns for a Drimeotus (Coleoptera, Leiodidae, Leptodirini) Cave Population',
            keywords: 'Cave beetles |  population dynamics |  markresight |  migration |  cave climate |  Carpathians |  Romania',
            link: 'https://subtbiol.pensoft.net/article/1308/',
            type: 'Journal Article'
          },
          {
            no: 2,
            reference: 'Lee R. F. D. Knight et al., “The Groundwater Invertebrate Fauna of the Channel Islands,” Subterranean Biology 15 (May 25, 2015): 69–94, https://doi.org/10.3897/subtbiol.15.4792.',
            authors: 'Glenn Longley',
            year: 2015,
            title: 'The Groundwater Invertebrate Fauna of the Channel Islands',
            keywords: 'Niphargus | Antrobathynella | Channel Islands | groundwater | stygobitic',
            link: 'https://subtbiol.pensoft.net/article/4792/',
            type: 'Journal Article'
          },
        ],

        article: {
          form: false,
          id: null,
          title: '',
          titleRule: [
            v => !!v || 'Title is required',
          ],
          reference: '',
          referenceRule: [
            v => !!v || 'Reference is required',
          ],
          link: '',
          linkRule: [
            v => !!v || 'Link is required',
            v => this.isURL(v) || "URL is not valid",
          ],
          year: '',
          yearRule: [
            v => !!v || 'Year is required',
            v => v.length <= 4 || 'Year must be less than 40 characters',
            v => {
              if (v > 1800 && v < 2030) return true
              return 'Year is not valid'
            },
          ],
          categoryId: null,
          category: '',
          categoryRule: [
            v => !!v || 'Category is required',
          ],
          authors: [],
          keywords: [],
          articles: [],
        },

        author: {
          form: false,
          id: null,
          name: '',
          nameRule: [
            v => !!v || 'Name is required',
            v => v.length <= 40 || 'Name must be less than 40 characters',
          ],
          authors: [],
        },

        keyword: {
          form: false,
          id: null,
          name: '',
          nameRule: [
            v => !!v || 'Name is required',
            v => v.length <= 40 || 'Name name must be less than 40 characters',
          ],
          keywords: [],
        },

        category: {
          form: false,
          id: null,
          name: '',
          nameRule: [
            v => !!v || 'Category name is required',
            v => v.length <= 40 || 'Category name must be less than 40 characters',
          ],
          categories: [],
        },

        file: null,

        loading: false,

    }
  },


  mounted() {
    this.indexArticle();
    this.indexAuthor();
    this.indexKeyword();
    this.indexCategory();
  },


  methods: {
    alertMessage(status, message) {
      this.alert.status = status;
      this.alert.message = message;
      this.alert.show = true;

      setTimeout(() => {
        this.alert.show = false;
        this.alert.status = '';
        this.alert.message = '';
      }, 2000)
    },
    resetFile () {
      this.$refs.file.reset();
    },
    resetAuthor () {
      this.$refs.author.reset();
    },
    resetKeyword () {
      this.$refs.keyword.reset();
    },
    resetCategory() {
      this.$refs.category.reset();
    },
    resetArticle() {
      this.$refs.article.reset();
    },
    isURL(str) {
      let url;
      try {
        url = new URL(str);
      } catch (_) {
        return false;
      }
      return url.protocol === "http:" || url.protocol === "https:";
    },

    indexArticle() {
      var self = this;
      var params = new URLSearchParams();
      params.append('action', 'article_list');

      axios.post('/wp-admin/admin-ajax.php', params)
        .then(function (response) {
          self.article.articles = response.data;
        });
    },
    createArticle() {
      var self = this;

      var params = new URLSearchParams();
      params.append('action', 'article_create');
      params.append('title', self.article.title);
      params.append('reference', self.article.reference);
      params.append('link', self.article.link);
      params.append('year', self.article.year);
      params.append('category', self.article.category);
      params.append('authors', self.article.authors);
      params.append('keywords', self.article.keywords);

      axios.post('/wp-admin/admin-ajax.php', params )
        .then( function (response) {
          console.log(response.data);
          self.alertMessage(response.data.alert.status, response.data.alert.message);
        })
        .finally(function() {
          self.resetArticle();
          self.indexArticle();
        });
    },
    selectArticle(id, title, reference, link, year, category_id, category, authors, keywords) {
      this.resetArticle();

      this.article.id = id;
      this.article.title = title;
      this.article.reference = reference;
      this.article.link = link;
      this.article.year = year;
      this.article.categoryId = category_id;
      this.article.category = category;

      for (var i = 0; i < authors.length; i++) {
        this.article.authors.push(authors[i].user_id);
      }

      for (var i = 0; i < keywords.length; i++) {
        this.article.keywords.push(parseInt(keywords[i].term_id));
      }
    },
    editArticle() {
      var self = this;
      var params = new URLSearchParams();
      params.append('action', 'article_edit');
      params.append('id', self.article.id);
      params.append('title', self.article.title);
      params.append('reference', self.article.reference);
      params.append('link', self.article.link);
      params.append('year', self.article.year);
      if (Number.isInteger(self.article.category)) {
        params.append('category_id', self.article.category);
      } else {
        params.append('category_id', self.article.categoryId);
      }
      params.append('authors', self.article.authors);
      params.append('keywords', self.article.keywords);

      axios.post('/wp-admin/admin-ajax.php', params )
        .then( function (response) {
          self.alertMessage(response.data.alert.status, response.data.alert.message);
        })
        .finally(function() {
          self.indexArticle();
          self.resetArticle();
        });

    },
    deleteArticle() {
      var self = this;
      var params = new URLSearchParams();
      params.append('action', 'article_delete');
      params.append('id', self.article.id);

      axios.post('/wp-admin/admin-ajax.php', params )
        .then( function (response) {
          self.alertMessage(response.data.alert.status, response.data.alert.message);
        })
        .finally(function() {
          self.indexArticle();
          self.resetArticle();
        });
    },

    indexAuthor() {
      var self = this;
      var params = new URLSearchParams();
      params.append('action', 'author_list');

      axios.post('/wp-admin/admin-ajax.php', params)
        .then(function (response) {
          self.author.authors = response.data;
        });
    },
    createAuthor() {
      var self = this;
      var params = new URLSearchParams();
      params.append('action', 'author_create');
      params.append('name', self.author.name);

      axios.post('/wp-admin/admin-ajax.php', params )
        .then( function (response) {
          self.alertMessage(response.data.alert.status, response.data.alert.message);
        })
        .finally(function() {
          self.resetAuthor();
          self.indexAuthor();
          self.indexArticle();
        });
    },
    selectAuthor(id, name) {
      this.author.id = id;
      this.author.name = name;
    },
    editAuthor() {
      var self = this;
      var params = new URLSearchParams();
      params.append('action', 'author_edit');
      params.append('id', self.author.id);
      params.append('name', self.author.name);

      axios.post('/wp-admin/admin-ajax.php', params )
        .then( function (response) {
          self.alertMessage(response.data.alert.status, response.data.alert.message);
        })
        .finally(function() {
          self.resetAuthor();
          self.indexAuthor();
          self.indexArticle();
        });
    },
    deleteAuthor() {
      var self = this;
      var params = new URLSearchParams();
      params.append('action', 'author_delete');
      params.append('id', self.author.id);

      axios.post('/wp-admin/admin-ajax.php', params )
        .then( function (response) {
          self.alertMessage(response.data.alert.status, response.data.alert.message);
        })
        .finally(function() {
          self.resetAuthor();
          self.indexAuthor();
          self.indexArticle();
        });
    },

    indexKeyword() {
      var self = this;
      var params = new URLSearchParams();
      params.append('action', 'keyword_list');

      axios.post('/wp-admin/admin-ajax.php', params)
        .then(function (response) {
          self.keyword.keywords = response.data;
        });
    },
    createKeyword() {
      var self = this;
      var params = new URLSearchParams();
      params.append('action', 'keyword_create');
      params.append('name', self.keyword.name);

      axios.post('/wp-admin/admin-ajax.php', params )
        .then( function (response) {
          self.alertMessage(response.data.alert.status, response.data.alert.message);
        })
        .finally(function() {
          self.resetKeyword();
          self.indexKeyword();
          self.indexArticle();
        });
    },
    selectKeyword(id, name) {
      this.keyword.id = id;
      this.keyword.name = name;
    },
    editKeyword() {
      var self = this;
      var params = new URLSearchParams();
      params.append('action', 'keyword_edit');
      params.append('id', self.keyword.id);
      params.append('name', self.keyword.name);

      axios.post('/wp-admin/admin-ajax.php', params )
        .then( function (response) {
          self.alertMessage(response.data.alert.status, response.data.alert.message);
        })
        .finally(function() {
          self.resetKeyword();
          self.indexKeyword();
          self.indexArticle();
        });
    },
    deleteKeyword() {
      var self = this;
      var params = new URLSearchParams();
      params.append('action', 'keyword_delete');
      params.append('id', self.keyword.id);

      axios.post('/wp-admin/admin-ajax.php', params )
        .then( function (response) {
          self.alertMessage(response.data.alert.status, response.data.alert.message);
        })
        .finally(function() {
          self.resetKeyword();
          self.indexKeyword();
          self.indexArticle();
        });
    },

    indexCategory() {
      var self = this;
      var params = new URLSearchParams();
      params.append('action', 'category_list');

      axios.post('/wp-admin/admin-ajax.php', params)
        .then(function (response) {
          self.category.categories = response.data;
        });
    },
    createCategory() {
      var self = this;
      var params = new URLSearchParams();
      params.append('action', 'category_create');
      params.append('name', self.category.name);

      axios.post('/wp-admin/admin-ajax.php', params )
        .then( function (response) {
          self.alertMessage(response.data.alert.status, response.data.alert.message);
        })
        .finally(function() {
          self.resetCategory();
          self.indexCategory();
        });
      
    },
    selectCategory(id, name) {
      this.category.id = id;
      this.category.name = name;
    },
    editCategory() {
      var self = this;
      var params = new URLSearchParams();
      params.append('action', 'category_edit');
      params.append('id', self.category.id);
      params.append('name', self.category.name);

      axios.post('/wp-admin/admin-ajax.php', params )
        .then( function (response) {
          self.alertMessage(response.data.alert.status, response.data.alert.message);
        })
        .finally(function() {
          self.resetCategory();
          self.indexCategory();
          self.indexArticle();
        });
    },
    deleteCategory() {
      var self = this;
      var params = new URLSearchParams();
      params.append('action', 'category_delete');
      params.append('id', self.category.id);

      axios.post('/wp-admin/admin-ajax.php', params )
        .then( function (response) {
          self.alertMessage(response.data.alert.status, response.data.alert.message);
        })
        .finally(function() {
          self.resetCategory();
          self.indexCategory();
          self.indexArticle();
        });
    },

    readFile(event) {
      var self = this;
      var files = event.target.files[0];
      Papa.parse(files, {
        skipEmptyLines: true,
        complete: function(results) {
          self.saveFile(results.data);
        }
      });
    },
    async saveFile(data) {
      var self = this;
      var params = new URLSearchParams();

      self.loading = true;

      for (var i = 0; i < data.length; i++) {
        try {
          if (i === 0) { continue; }
          await axios.post('/wp-admin/admin-ajax.php', 
            new URLSearchParams({
              'action': 'file_create',
              'reference': data[i][1],
              'authors': data[i][2],
              'year': data[i][3],
              'title': data[i][4],
              'keywords': data[i][5],
              'link': data[i][6],
              'category': data[i][7],
            }))
          .then( function (response) {
            // console.log(response.data);
          })
        } catch (error) {
          // console.log(error);
        }
      }

      self.loading = false;
      self.resetFile();
      self.alertMessage('success', 'File Has Been Uploaded');
      self.indexArticle();
      self.indexAuthor();
      self.indexKeyword();
      self.indexCategory();
    }

  }
})

app.use(vuetify).mount('#cave-inv')

