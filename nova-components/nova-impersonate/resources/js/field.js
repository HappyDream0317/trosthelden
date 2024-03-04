import IndexField from './components/Index/ImpersonateField'
import DetailField from './components/Detail/ImpersonateField'

Nova.booting((app, store) => {
  app.config.devtools = true;
  app.component('index-impersonate-field', IndexField)
  app.component('detail-impersonate-field', DetailField)
})
