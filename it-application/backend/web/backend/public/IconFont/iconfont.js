;(function(window) {

  var svgSprite = '<svg>' +
    '' +
    '<symbol id="icon-zuo" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M368.416 511.968c-0.992 13.184 3.264 26.656 13.312 36.928l192.128 192.416c18.688 18.592 48.896 18.624 67.68 0 18.688-18.656 18.592-48.992 0.128-67.648l-161.792-161.728 161.824-161.6c18.496-18.752 18.784-48.832 0.064-67.584-18.88-18.688-48.992-18.624-67.744 0l-192.288 192.16C371.584 485.088 367.392 498.56 368.416 511.968L368.416 511.968zM550.624 512.224"  ></path>' +
    '' +
    '<path d="M512 65.088c246.4 0 446.88 200.512 446.88 446.912S758.432 958.912 512 958.912c-246.432 0-446.944-200.512-446.944-446.912S265.568 65.088 512 65.088zM512 885.76c206.08 0 373.76-167.648 373.76-373.76S718.112 138.24 512 138.24 138.24 305.92 138.24 512 305.92 885.76 512 885.76z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-cog" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M838.877643 431.952C832.426643 405.578 823.020643 380.367 810.770643 356.853L867.591643 286.401C879.676643 271.512 879.127643 250.089 866.386643 235.719L831.085643 195.989C818.289643 181.634 797.070643 178.598 780.883643 188.824L704.977643 236.54C671.591643 213.214 633.773643 195.989 592.977643 185.994L578.127643 96.759C575.011643 77.864 558.660643 64 539.466643 64L486.268643 64C467.113643 64 450.707643 77.864 447.659643 96.759L432.729643 186.022C399.000643 194.28 367.376643 207.61 338.516643 225.166L266.328643 173.648C250.770643 162.533 229.401643 164.271 215.824643 177.819L178.241643 215.429C164.693643 229.006 162.955643 250.375 174.099643 265.933L225.737643 338.259C208.346643 366.901 195.153643 398.345 186.896643 431.773L97.153643 446.759C78.287643 449.875 64.396643 466.228 64.396643 485.422L64.396643 538.578C64.396643 557.772 78.287643 574.125 97.153643 577.241L186.896643 592.227C193.677643 619.734 203.521643 646.011 216.605643 670.374L160.043643 740.428C147.999643 755.303 148.505643 776.74 161.246643 791.097L196.520643 830.827C209.317643 845.209 230.548643 848.19 246.736643 837.99L323.736643 789.62C356.317643 811.905 393.176643 828.284 432.729643 837.99L447.659643 927.24C450.707643 946.137 467.113643 960 486.268643 960L539.466643 960C558.660643 960 575.011643 946.137 578.127643 927.241L593.002643 837.991C626.310643 829.844 657.533643 816.746 686.136643 799.491L761.332643 853.196C776.863643 864.352 798.247643 862.602 811.810643 849.011L849.407643 811.414C862.940643 797.878 864.773643 776.521 853.534643 760.936L799.998643 685.877C817.440643 657.139 830.702643 625.639 838.907643 592.061L927.665643 577.24C946.586643 574.124 960.421643 557.771 960.421643 538.577L960.421643 485.421C960.394643 466.228 946.559643 449.875 927.636643 446.759L838.877643 431.952ZM512.3975 657C432.885027 657 368.396643 592.276042 368.396643 512.5 368.396643 432.711917 432.885027 368 512.3975 368 591.896259 368 656.396643 432.711917 656.396643 512.5 656.396643 592.276042 591.896259 657 512.3975 657L512.3975 657Z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-you" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M698.25 512.032c0.992-13.184-3.264-26.657-13.312-36.928l-192.128-192.416c-18.688-18.592-48.896-18.624-67.68 0-18.688 18.656-18.592 48.992-0.128 67.648l161.792 161.728-161.824 161.6c-18.496 18.752-18.784 48.832-0.064 67.584 18.88 18.688 48.992 18.624 67.744 0l192.288-192.16c10.143-10.176 14.336-23.648 13.312-37.057v0zM516.042 511.776z"  ></path>' +
    '' +
    '<path d="M512 958.913c-246.4 0-446.879-200.512-446.879-446.912s200.449-446.912 446.879-446.912c246.432 0 446.945 200.512 446.945 446.912s-200.512 446.912-446.945 446.912zM512 138.24c-206.081 0-373.76 167.648-373.76 373.76s167.648 373.76 373.76 373.76 373.76-167.68 373.76-373.76-167.68-373.76-373.76-373.76z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-protruding" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M382.30016 250.60352c0-16.96768 13.75232-30.72 30.72-30.72l468.46976 0c16.96768 0 30.72 13.75232 30.72 30.72s-13.75232 30.72-30.72 30.72L413.02016 281.32352C396.05248 281.32352 382.30016 267.56096 382.30016 250.60352zM881.48992 742.67648 413.02016 742.67648c-16.96768 0-30.72 13.75232-30.72 30.72s13.75232 30.72 30.72 30.72l468.46976 0c16.96768 0 30.72-13.75232 30.72-30.72S898.4576 742.67648 881.48992 742.67648zM195.42016 281.32352l107.50976 0c16.96768 0 30.72-13.75232 30.72-30.72s-13.75232-30.72-30.72-30.72L195.42016 219.88352c-16.96768 0-30.72 13.75232-30.72 30.72S178.45248 281.32352 195.42016 281.32352zM302.92992 742.67648 195.42016 742.67648c-16.96768 0-30.72 13.75232-30.72 30.72s13.75232 30.72 30.72 30.72l107.50976 0c16.96768 0 30.72-13.75232 30.72-30.72S319.8976 742.67648 302.92992 742.67648zM413.02016 456.86784l310.20032 0c16.96768 0 30.72-13.75232 30.72-30.72s-13.75232-30.72-30.72-30.72L413.02016 395.42784c-16.96768 0-30.72 13.75232-30.72 30.72S396.05248 456.86784 413.02016 456.86784zM413.02016 626.06336l310.20032 0c16.96768 0 30.72-13.75232 30.72-30.72s-13.75232-30.72-30.72-30.72L413.02016 564.62336c-16.96768 0-30.72 13.75232-30.72 30.72S396.05248 626.06336 413.02016 626.06336zM269.88544 418.83648c-12.00128-11.99104-31.4368-11.99104-43.43808 0-12.00128 11.99104-12.00128 31.44704 0 43.44832l12.71808 12.72832-96.65536 0c-16.96768 0-30.72 13.75232-30.72 30.72s13.75232 30.72 30.72 30.72l96.65536 0-12.71808 12.72832c-12.00128 11.99104-12.00128 31.44704 0 43.43808 6.00064 6.00064 13.86496 9.00096 21.71904 9.00096s15.7184-3.00032 21.71904-9.00096l65.16736-65.16736c5.76512-5.76512 9.00096-13.57824 9.00096-21.71904 0-8.15104-3.23584-15.96416-9.00096-21.72928L269.88544 418.83648z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-indentation" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M382.30016 250.60352c0-16.96768 13.75232-30.72 30.72-30.72l468.46976 0c16.96768 0 30.72 13.75232 30.72 30.72s-13.75232 30.72-30.72 30.72L413.02016 281.32352C396.05248 281.32352 382.30016 267.56096 382.30016 250.60352zM881.48992 742.67648 413.02016 742.67648c-16.96768 0-30.72 13.75232-30.72 30.72s13.75232 30.72 30.72 30.72l468.46976 0c16.96768 0 30.72-13.75232 30.72-30.72S898.4576 742.67648 881.48992 742.67648zM195.42016 281.32352l107.50976 0c16.96768 0 30.72-13.75232 30.72-30.72s-13.75232-30.72-30.72-30.72L195.42016 219.88352c-16.96768 0-30.72 13.75232-30.72 30.72S178.45248 281.32352 195.42016 281.32352zM302.92992 742.67648 195.42016 742.67648c-16.96768 0-30.72 13.75232-30.72 30.72s13.75232 30.72 30.72 30.72l107.50976 0c16.96768 0 30.72-13.75232 30.72-30.72S319.8976 742.67648 302.92992 742.67648zM413.02016 456.86784l310.20032 0c16.96768 0 30.72-13.75232 30.72-30.72s-13.75232-30.72-30.72-30.72L413.02016 395.42784c-16.96768 0-30.72 13.75232-30.72 30.72S396.05248 456.86784 413.02016 456.86784zM413.02016 626.06336l310.20032 0c16.96768 0 30.72-13.75232 30.72-30.72s-13.75232-30.72-30.72-30.72L413.02016 564.62336c-16.96768 0-30.72 13.75232-30.72 30.72S396.05248 626.06336 413.02016 626.06336zM185.9584 592.64c12.00128 11.99104 31.4368 11.99104 43.43808 0 12.00128-11.99104 12.00128-31.44704 0-43.44832l-12.71808-12.72832L313.344 536.46336c16.96768 0 30.72-13.75232 30.72-30.72s-13.75232-30.72-30.72-30.72L216.6784 475.02336l12.71808-12.72832c12.00128-11.99104 12.00128-31.44704 0-43.43808-6.00064-6.00064-13.86496-9.00096-21.71904-9.00096s-15.7184 3.00032-21.71904 9.00096l-65.16736 65.16736c-5.76512 5.76512-9.00096 13.57824-9.00096 21.71904 0 8.15104 3.23584 15.96416 9.00096 21.72928L185.9584 592.64z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-systole" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M10.039216 190.745098l1003.921569 0 0 80.313725-1003.921569 0 0-80.313725ZM10.039216 471.843137l1003.921569 0 0 80.313725-1003.921569 0 0-80.313725ZM10.039216 752.941176l1003.921569 0 0 80.313725-1003.921569 0 0-80.313725Z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-expand" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M257.096 191.17v642.11h-65.119v-642.11h65.119z"  ></path>' +
    '' +
    '<path d="M541.701 191.17v642.11h-65.119v-642.11h65.119z"  ></path>' +
    '' +
    '<path d="M833.73 191.169v642.11h-65.119v-642.11h65.119z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-downBottom" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M511.722172 639.532605 768.166799 383.094118 255.277546 383.094118Z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-downRight-copy" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M639.533 512.278l-256.438-256.445v512.889z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '</svg>'
  var script = function() {
    var scripts = document.getElementsByTagName('script')
    return scripts[scripts.length - 1]
  }()
  var shouldInjectCss = script.getAttribute("data-injectcss")

  /**
   * document ready
   */
  var ready = function(fn) {
    if (document.addEventListener) {
      if (~["complete", "loaded", "interactive"].indexOf(document.readyState)) {
        setTimeout(fn, 0)
      } else {
        var loadFn = function() {
          document.removeEventListener("DOMContentLoaded", loadFn, false)
          fn()
        }
        document.addEventListener("DOMContentLoaded", loadFn, false)
      }
    } else if (document.attachEvent) {
      IEContentLoaded(window, fn)
    }

    function IEContentLoaded(w, fn) {
      var d = w.document,
        done = false,
        // only fire once
        init = function() {
          if (!done) {
            done = true
            fn()
          }
        }
        // polling for no errors
      var polling = function() {
        try {
          // throws errors until after ondocumentready
          d.documentElement.doScroll('left')
        } catch (e) {
          setTimeout(polling, 50)
          return
        }
        // no errors, fire

        init()
      };

      polling()
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

  var before = function(el, target) {
    target.parentNode.insertBefore(el, target)
  }

  /**
   * Prepend el to target
   *
   * @param {Element} el
   * @param {Element} target
   */

  var prepend = function(el, target) {
    if (target.firstChild) {
      before(el, target.firstChild)
    } else {
      target.appendChild(el)
    }
  }

  function appendSvg() {
    var div, svg

    div = document.createElement('div')
    div.innerHTML = svgSprite
    svgSprite = null
    svg = div.getElementsByTagName('svg')[0]
    if (svg) {
      svg.setAttribute('aria-hidden', 'true')
      svg.style.position = 'absolute'
      svg.style.width = 0
      svg.style.height = 0
      svg.style.overflow = 'hidden'
      prepend(svg, document.body)
    }
  }

  if (shouldInjectCss && !window.__iconfont__svg__cssinject__) {
    window.__iconfont__svg__cssinject__ = true
    try {
      document.write("<style>.svgfont {display: inline-block;width: 1em;height: 1em;fill: currentColor;vertical-align: -0.1em;font-size:16px;}</style>");
    } catch (e) {
      console && console.log(e)
    }
  }

  ready(appendSvg)


})(window)