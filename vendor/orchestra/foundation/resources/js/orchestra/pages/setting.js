import App from '../app'
import Javie from '../../vendor/javie'
import $ from '../../vendor/jquery'

let Setting = App.extend({
  data() {
    return {
      sidebar: {
        active: 'settings'
      }
    }
  },

  ready() {
    const vm = this

    $(() => {
      const driver = $('select[name="email_driver"]')

      driver.on('change', () => {
        vm.driver(driver.val() || 'mail')
      })

      driver.trigger('change')
    })
  },

  methods: {
    driver(name) {
      this.container('input[name^="email_"]').addClass('hidden')
      this.container('select[name^="email_region"]').addClass('hidden')
      this.container('input[name="email_queue"]').addClass('hidden')

      Javie.trigger('setting.changed: email.driver', [name, this])
      Javie.trigger(`setting.changed: email.driver.${name}`, [this])

      this.container('input[name^="email_address"]').removeClass('hidden')
    },

    container(node) {
      return $(node).parent().parent().parent()
    },
  }
})

export default Setting
