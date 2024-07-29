!function(o){o(document).ready((function(){var e=o(".real3dflipbook");e.length>0&&o.each(e,(function(){o(this).attr("id");var e=o(this).data("flipbook-options");if(this.removeAttribute("data-flipbook-options"),e.assets={preloader:e.rootFolder+"assets/images/preloader.jpg",left:e.rootFolder+"assets/images/left.png",overlay:e.rootFolder+"assets/images/overlay.jpg",flipMp3:e.rootFolder+"assets/mp3/turnPage.mp3",shadowPng:e.rootFolder+"assets/images/shadow.png",spinner:e.rootFolder+"assets/images/spinner.gif"},e.pdfjsworkerSrc=e.rootFolder+"js/libs/pdf.worker.min.js?ver="+e.version,e.flipbookSrc=e.rootFolder+"js/flipbook.min.js?ver="+e.version,e.cMapUrl=e.rootFolder+"assets/cmaps/",function e(t){o.each(t,(function(o,i){"object"==typeof i||"array"==typeof i?e(i):isNaN(i)?"true"==i?t[o]=!0:"false"==i&&(t[o]=!1):""===t[o]?delete t[o]:t[o]=Number(i)}))}(e),e=function o(e){for(var t in e)"string"==typeof e[t]?e[t]=(e[t]+"").replace(/\\(.?)/g,(function(o,e){switch(e){case"\\":return"\\";case"0":return"\0";case"":return"";default:return e}})):"object"==typeof e[t]&&(e[t]=o(e[t]));return e}(e),e.s||(e.logoImg=e.rootFolder+"assets/images/logo_dark.png",e.logoUrl="https://real3dflipbook.com",e.logoCSS="position:absolute;width:200px;margin:20px;top:0;right:0;",e.logo=!0),e.pages){if(!Array.isArray(e.pages)){var t=[];for(var i in e.pages)t[i]=e.pages[i];e.pages=t}for(var i in e.pages)e.pages[i].htmlContent&&(e.pages[i].htmlContent=unescape(e.pages[i].htmlContent)),e.pages[i].items&&e.pages[i].items.forEach((function(o,t){e.pages[i].items[t].url&&(e.pages[i].items[t].url=unescape(e.pages[i].items[t].url))}))}e.social=[],e.btnDownloadPages&&e.btnDownloadPages.url&&(e.btnDownloadPages.url=e.btnDownloadPages.url.replace(/\\/g,"/")),e.btnDownloadPdf&&(e.btnDownloadPdfUrl?e.btnDownloadPdf.url=e.btnDownloadPdfUrl.replace(/\\/g,"/"):e.btnDownloadPdf&&e.btnDownloadPdf.url?e.btnDownloadPdf.url=e.btnDownloadPdf.url.replace(/\\/g,"/"):e.pdfUrl&&(e.btnDownloadPdf.url=e.pdfUrl.replace(/\\/g,"/")));var a=o(this),l=this,n=(l.parentNode,/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)||navigator.maxTouchPoints&&navigator.maxTouchPoints>2&&/MacIntel/.test(navigator.platform));e.mode=n&&e.modeMobile?e.modeMobile:e.mode,e.doubleClickZoomDisabled=!e.doubleClickZoom,e.pageDragDisabled=!e.pageDrag;var s,r,d=(s={},window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,(function(o,e,t){s[e]=t.split("#")[0]})),s);for(var i in d)-1!=i.indexOf("r3d-")&&(e[i.replace("r3d-","")]=decodeURIComponent(d[i]));switch(n&&e.modeMobile&&(e.mode=e.modeMobile),e.shareImage=e.shareImage||e.lightboxThumbnailUrl,e.mode){case"normal":l.className+="-"+l.id,e.lightBox=!1,a.css("position","relative").css("display","block").css("width","100%");let t=l.getBoundingClientRect().width;t<e.responsiveViewTreshold?l.style.height=t/.65+"px":l.style.height=t/1.3+"px",r=a.flipBook(e);break;case"lightbox":if(a.css("display","inline"),e.lightBox=!0,l.className+="-"+l.id,a.attr("style",e.lightboxContainerCSS),e.hideThumbnail&&(e.lightboxThumbnailUrl=""),e.lightboxText=e.lightboxText||"",e.showTitle&&(e.lightboxText+=e.name),e.showDate&&(e.lightboxText+=e.date),e.lightboxThumbnailUrl&&""!=e.lightboxThumbnailUrl){"https:"==location.protocol?e.lightboxThumbnailUrl=e.lightboxThumbnailUrl.replace("http://","https://"):"http:"==location.protocol&&(e.lightboxThumbnailUrl=e.lightboxThumbnailUrl.replace("https://","http://"));var g=o("<div>").attr("style","position: relative;").appendTo(a),p=o("<img></img>").attr("src",e.lightboxThumbnailUrl).appendTo(g).attr("style",e.lightboxThumbnailUrlCSS);if(e.thumbAlt&&p.attr("alt",e.thumbAlt),e.lightboxThumbnailInfo){var h=o("<span>").appendTo(g).attr("style","position: absolute; display: grid; align-items: center; text-align: center; top: 0;  width: 100%; height: 100%; font-size: 16px; color: #000; background: rgba(255,255,255,.8); "+e.lightboxThumbnailInfoCSS).text(e.lightboxThumbnailInfoText||e.name).hide();g.hover((function(){h.fadeIn("fast")}),(function(){h.fadeOut("fast")}))}}else!e.lightboxText&&e.lightboxCssClass&&a.css("display","none");if(e.lightboxText&&""!=e.lightboxText){var b=o("<span>").text(e.lightboxText),c="text-align:center; padding: 10px 0;";c+=e.lightboxTextCSS,"top"==e.lightboxTextPosition?b.prependTo(a):b.appendTo(a),b.attr("style",c)}e.lightboxCssClass&&""!=e.lightboxCssClass?a.addClass(e.lightboxCssClass):e.lightboxCssClass=l.className,e.lightboxLink?o("."+e.lightboxCssClass).click((function(){var o=e.lightboxLinkNewWindow?"_blank":"_self";window.open(e.lightboxLink,o)})):r=o("."+e.lightboxCssClass).flipBook(e);break;case"fullscreen":if(e.lightBox=!1,a.appendTo("body").addClass("flipbook-browser-fullscreen"),r=a.flipBook(e),o("body").css("overflow","hidden"),e.menuSelector){var u=o(e.menuSelector),f=window.innerHeight-u.height();a.css("top",u.height()+"px").css("height",f),window.onresize=function(o){f=window.innerHeight-u.height(),a.css("top",u.height()+"px").css("height",f)}}}function m(e,t){const i={bookId:r.options.bookId,action:t,nonce:r3d.nonce};for(let o in e)i[o]=e[o];o.ajax({type:"POST",url:r3d.ajax_url,data:i,success:function(o,e,t){},error:function(o,e,t){}})}r&&r.on&&(r.on("r3d-update-note",(function(o){m({note:o.note},"r3d_update_note")})),r.on("r3d-delete-note",(function(o){m({note:o.note},"r3d_delete_note")})),r.options.resumeReading&&jQuery(r).on("pagechange",(function(o){m({page:o.page.split("-")[0]},"r3d_last_page")})))}))}))}(jQuery);