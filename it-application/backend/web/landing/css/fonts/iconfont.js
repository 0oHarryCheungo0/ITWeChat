;(function(window) {

var svgSprite = '<svg>' +
  ''+
    '<symbol id="icon-denglu" viewBox="0 0 1024 1024">'+
      ''+
      '<path d="M512 0C229.248 0 0 229.248 0 512s229.248 512 512 512c282.752 0 512-229.248 512-512S794.752 0 512 0zM512 990.016c-264 0-478.016-214.016-478.016-478.016S248 33.984 512 33.984 990.016 248 990.016 512 776 990.016 512 990.016z"  ></path>'+
      ''+
      '<path d="M508.032 277.312c-115.648 0-209.344 93.696-209.344 209.344 0 115.584 93.696 209.344 209.344 209.344 115.584 0 209.344-93.76 209.344-209.344C717.312 371.072 623.552 277.312 508.032 277.312zM507.968 665.92c-99.008 0-179.264-80.256-179.264-179.328 0-99.008 80.256-179.264 179.264-179.264 99.008 0 179.328 80.256 179.328 179.264C687.296 585.664 606.976 665.92 507.968 665.92z"  ></path>'+
      ''+
      '<path d="M225.792 914.112C224 904.576 222.784 894.72 222.784 884.608c0-88.384 71.616-160 160-160l252.736 0c88.384 0 160.064 71.616 160.064 160 0 10.368-1.216 20.48-3.072 30.336 11.2-7.936 22.464-16.512 33.728-25.536 0-1.344 0.384-2.624 0.384-4.032 0-104.64-84.736-189.376-189.312-189.376l-256 0C276.8 696 192 780.736 192 885.376c0 1.408 0.384 2.688 0.384 4.096C202.048 897.152 213.12 905.408 225.792 914.112z"  ></path>'+
      ''+
    '</symbol>'+
  ''+
    '<symbol id="icon-mima" viewBox="0 0 1024 1024">'+
      ''+
      '<path d="M704 774.4 320 774.4c-44.8 0-83.2-32-83.2-76.8L236.8 467.2C236.8 422.4 268.8 384 320 384l32 0L352 320c0-89.6 64-160 160-160 102.4 0 160 76.8 160 160l0 64L704 384c44.8 0 83.2 38.4 83.2 83.2l0 230.4C787.2 736 748.8 774.4 704 774.4L704 774.4zM640 320c0-64-51.2-128-128-128C435.2 192 384 256 384 320l0 64 256 0L640 320 640 320zM755.2 467.2c0-19.2-25.6-51.2-51.2-51.2L320 416c-25.6 0-51.2 25.6-51.2 51.2l0 224c0 19.2 25.6 51.2 51.2 51.2L704 742.4c25.6 0 51.2-25.6 51.2-51.2L755.2 467.2 755.2 467.2zM524.8 576l0 64c0 12.8-6.4 12.8-19.2 12.8-12.8 0-19.2-6.4-19.2-12.8L486.4 576C480 569.6 467.2 556.8 467.2 544c0-19.2 19.2-38.4 44.8-38.4 25.6 0 44.8 19.2 44.8 38.4C556.8 556.8 544 569.6 524.8 576L524.8 576z"  ></path>'+
      ''+
      '<path d="M512 1024c-281.6 0-512-230.4-512-512s230.4-512 512-512 512 230.4 512 512S793.6 1024 512 1024zM512 38.4C249.6 38.4 38.4 249.6 38.4 512s211.2 473.6 473.6 473.6 473.6-211.2 473.6-473.6S774.4 38.4 512 38.4z"  ></path>'+
      ''+
    '</symbol>'+
  ''+
'</svg>'
var script = function() {
    var scripts = document.getElementsByTagName('script')
    return scripts[scripts.length - 1]
  }()
var shouldInjectCss = script.getAttribute("data-injectcss")

/**
 * document ready
 */
var ready = function(fn){
  if(document.addEventListener){
      document.addEventListener("DOMContentLoaded",function(){
          document.removeEventListener("DOMContentLoaded",arguments.callee,false)
          fn()
      },false)
  }else if(document.attachEvent){
     IEContentLoaded (window, fn)
  }

  function IEContentLoaded (w, fn) {
      var d = w.document, done = false,
      // only fire once
      init = function () {
          if (!done) {
              done = true
              fn()
          }
      }
      // polling for no errors
      ;(function () {
          try {
              // throws errors until after ondocumentready
              d.documentElement.doScroll('left')
          } catch (e) {
              setTimeout(arguments.callee, 50)
              return
          }
          // no errors, fire

          init()
      })()
      // trying to always fire before onload
      d.onreadystatechange = function() {
          if (d.readyState == 'complete') {
              d.onreadystatechange = null
              init()
          }
      }
  }
}

/**
 * Insert el before target
 *
 * @param {Element} el
 * @param {Element} target
 */

var before = function (el, target) {
  target.parentNode.insertBefore(el, target)
}

/**
 * Prepend el to target
 *
 * @param {Element} el
 * @param {Element} target
 */

var prepend = function (el, target) {
  if (target.firstChild) {
    before(el, target.firstChild)
  } else {
    target.appendChild(el)
  }
}

function appendSvg(){
  var div,svg

  div = document.createElement('div')
  div.innerHTML = svgSprite
  svg = div.getElementsByTagName('svg')[0]
  if (svg) {
    svg.setAttribute('aria-hidden', 'true')
    svg.style.position = 'absolute'
    svg.style.width = 0
    svg.style.height = 0
    svg.style.overflow = 'hidden'
    prepend(svg,document.body)
  }
}

if(shouldInjectCss && !window.__iconfont__svg__cssinject__){
  window.__iconfont__svg__cssinject__ = true
  try{
    document.write("<style>.svgfont {display: inline-block;width: 1em;height: 1em;fill: currentColor;vertical-align: -0.1em;font-size:16px;}</style>");
  }catch(e){
    console && console.log(e)
  }
}

ready(appendSvg)


})(window)
