//Listen only allowed element in the lightbox image modal
add_action('wp_head', function () {

    ?>

    <script>

      document.addEventListener('DOMContentLoaded', function () {

		document.addEventListener('click', function (e) {
		  var lightbox = e.target.closest('.elementor-lightbox');
		  if (!lightbox) return;

		  // If user clicked actual controls → let Elementor handle it
		  var allow = e.target.closest(
			'.dialog-close-button, ' +
			'.elementor-swiper-button, ' +
			'img'
		  );

		  if (allow) return;

		  // If it's the backdrop itself → allow close
		  if (e.target === lightbox) return;

		  // Otherwise, stop accidental close
		  e.stopPropagation();
		}, true);


	  })
    </script>

    <?php

});

//Hide Elementor Device Mode Span from Screen Readers (Front End ONLY)
add_action('wp_head', function () {

    ?>

    <script>

      document.addEventListener('DOMContentLoaded', function () {

        const el = document.getElementById('elementor-device-mode');

        if (el) {

          el.setAttribute('aria-hidden', 'true');

        }

      });

    </script>

    <?php

});

 

//Remove GF Error Text Heading (Front End ONLY)
add_action('wp_footer', function () {

    ?>

    <script>

    (function() {

        function replaceErrorHeading() {

            const errorHeading = document.querySelector('.gform_validation_errors > h2');

            if (errorHeading) {

                const p = document.createElement('p');

                p.className = errorHeading.className;

                p.innerHTML = errorHeading.innerHTML;

                errorHeading.replaceWith(p);

            }

        }

 

        document.addEventListener('DOMContentLoaded', replaceErrorHeading);

 

        const observer = new MutationObserver(replaceErrorHeading);

        observer.observe(document.body, {

            childList: true,

            subtree: true

        });

    })();

    </script>

    <style>

    .gform_validation_errors {

        border: 1px solid #e04b39;

        background-color: #fdf2f2;

        padding: 12px 16px;

        color: #c02b0a;

        font-family: inherit;

        font-size: 15px;

        line-height: 32px;

        border-radius: 0 !important;

        text-align: left;

    }

 

    .gform_validation_errors p {

        margin: 0;

        font-weight: normal;

        padding: 0;

        color: inherit;

        line-height: inherit;

        font-size: inherit;

    }

 

    .gform_validation_errors .gform-icon {

        margin-right: 0.35em;

        vertical-align: text-top;

    }

    </style>

    <?php

});

// It requires "ally-accordion" class in the parent container of the accordion
// (IMPORTANT) If there are multiple accordions in the page, the class can be added to the parent of all accordions
// It restructures Elementor Accordion to remove arrow key navigation
// It also removes the aria-label from the accordion and the role="region" and aria-labelledby from the content
add_action('wp_footer', function () {
    ?>
    <script>
document.addEventListener('DOMContentLoaded', () => {
	  document.querySelectorAll('.ally-accordion .e-n-accordion').forEach((acc, index) => {
		  if(index === 0) {
			    const style = document.createElement('style');

				  style.textContent = `

					details[open] .e-con {

					  display: flex !important;

					}

				  `;

				  document.head.appendChild(style);
		  }
		  
	acc.removeAttribute('aria-label');
	let backgroundColor = undefined;
	acc.querySelectorAll('details').forEach(item => {
		const summary = item.querySelector('summary');
		if(summary) {
			backgroundColor = window.getComputedStyle(summary).backgroundColor;
		}
		
		item.querySelectorAll('[role="region"]').forEach(element => {
			element.removeAttribute('role');
			element.removeAttribute('aria-labelledby');
		});
		
		const div = document.createElement('div');
		div.className = item.className;
		div.appendChild(item);
		acc.appendChild(div);
	});
		  
	acc.querySelectorAll('summary').forEach(item => item.style.backgroundColor = backgroundColor || '#fff');
  
  const blockKeys = (e) => {
    
    const tag = e.target.tagName;
    const isEditable = e.target.isContentEditable || tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT';
    if (isEditable) return;

    
    const keysToBlock = ['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'];
    if (keysToBlock.includes(e.key)) {
      e.stopImmediatePropagation();
      e.preventDefault();
    }
  };

  acc.addEventListener('keydown', blockKeys, true);

  const restoreTabIndex = () => {
    acc.querySelectorAll('summary.e-n-accordion-item-title[tabindex]').forEach(s => {
      s.removeAttribute('tabindex');
    });
  };

  restoreTabIndex();

  const mo = new MutationObserver((mutations) => {
    for (const m of mutations) {
      if (m.type === 'attributes' && m.attributeName === 'tabindex' && m.target.matches('summary.e-n-accordion-item-title')) {
        m.target.removeAttribute('tabindex');
      }
    }
  });

  acc.querySelectorAll('summary.e-n-accordion-item-title').forEach(summary => {
    mo.observe(summary, { attributes: true, attributeFilter: ['tabindex'] });
  });
      })

});
    </script>
    <?php
  
});



// It requires "ally-als" class in the parent container of the accordion
// It add list semantics to the accordion, adding role list to the container of the accordion and role listitem to the direct children that contains an accordion item
add_action('wp_footer', function() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.ally-als').forEach(function (accordionWrapper) {
            const accordion = accordionWrapper.querySelector('.e-n-accordion');
            if (accordion) {
                accordion.setAttribute('role', 'list');
                Array.from(accordion.children).forEach(function (child) {
                    child.setAttribute('role', 'listitem');
                });
            }
        });
    });
    </script>
    <?php
});



// Add list semantics to galleries, it requires the class "ally-gls" in the parent container of the gallery. 
// It also adds aria-label to the links and alt text to the images in the modal, and an invisible alert with the current image number and total images when the modal is open.
add_action('wp_footer', function () {

    ?>
    <script>
    function createInvisibleAlert() {
        invisibleAlert = document.createElement("div");
        invisibleAlert.id = "invisible_alert_image";
        invisibleAlert.setAttribute("aria-live", "polite");
        invisibleAlert.classList.add("visually-hidden");
        document.body.appendChild(invisibleAlert);
        return invisibleAlert;
    }
    document.addEventListener('DOMContentLoaded', function () {
        createInvisibleAlert();
    
        document.querySelectorAll('.ally-gls').forEach(function (galleryWrapper) {
            const container = galleryWrapper.querySelector('.elementor-gallery__container');
            if (container) {
                container.setAttribute('role', 'list');
                container.setAttribute('aria-label', 'Gallery');
            
                const children = Array.from(container.children);
                children.forEach(function (child) {
                const wrapper = document.createElement('div');
                wrapper.style.pointerEvents = 'none';
                child.style.pointerEvents = 'auto';
                wrapper.setAttribute('role', 'listitem');
                wrapper.appendChild(child.cloneNode(true));
                container.replaceChild(wrapper, child);
            });
            }
            galleryWrapper.querySelectorAll('a').forEach(function (anchor) {
                anchor.setAttribute('role', 'button');

                const imgDiv = anchor.querySelector('div[role="img"]');
                if (imgDiv) {
                    const altText = imgDiv.getAttribute('aria-label') || imgDiv.getAttribute('alt') || 'Image';
                    anchor.setAttribute('aria-label', 'Full screen: ' + altText);
                }
            });
        });
    
    
        function updateAlertImgContainer() {
            setTimeout(() => {
                activeElement = document.querySelector(".swiper-slide.swiper-slide-active");
            
                if(!activeElement) return
            
                const invisibleAlert = document.querySelector("#invisible_alert_image");
            
                const index = (+activeElement.getAttribute("data-swiper-slide-index")) + 1;
                const total = document.querySelectorAll(".elementor-gallery__container div[role='img']").length;
                invisibleAlert.innerHTML = "";
                const text = document.createElement("span");
                text.innerText = `Image ${index} of ${total}`;
                invisibleAlert.appendChild(text);
            }, 200);
        }
    
        function setAltToModalImgs(dialog) {
            const imgsDialog = dialog.querySelectorAll(".swiper-slide:not(.swiper-slide-duplicate) img");
            const imgsNoDialog = document.querySelectorAll(".elementor-gallery__container div[role='img']");
            imgsNoDialog.forEach((item, index) => {
            const altText = item.getAttribute("aria-label");
            imgsDialog[index].setAttribute("alt", altText);
        });
        
            const imgsDuplicate = dialog.querySelectorAll(".swiper-slide-duplicate");
            if(imgsDuplicate.length > 1) {
                const imgFirst = imgsDuplicate[0].querySelector("img");
                const imgLast = imgsDuplicate[1].querySelector("img");
                const labelFirst = imgsNoDialog[0].getAttribute("aria-label");
                const labelLast = imgsNoDialog[imgsNoDialog.length -1].getAttribute("aria-label");
                imgFirst.setAttribute("alt", labelLast);
                imgLast.setAttribute("alt", labelFirst);
            }
        }
    
        const elements = document.querySelectorAll(".ally-gls .elementor-gallery__container a");
        elements.forEach(element => {
            element.addEventListener('click', () => {
                setTimeout(() => {
                const modal = document.querySelector('.dialog-widget');
                if (!modal) return;
            	
                modal.setAttribute("role", "dialog");
                updateAlertImgContainer();
                setAltToModalImgs(modal);
                updateAriaHidden(modal)
                const releaseTrap = trapFocus(modal, element);
					
				const imgActive = modal.querySelector(".swiper-slide-active img");
				if(imgActive) {
					imgActive.setAttribute("tabindex", "-1");
					imgActive.focus();
				}
            
                const observer = new MutationObserver(() => updateAriaHidden(modal));
                modal.querySelectorAll('.swiper-slide').forEach(slide => {
                    observer.observe(slide, { attributes: true, attributeFilter: ['class'] });
                });
            
                // Clear old observers
                if (modal.__observer) {
                    modal.__observer.disconnect();
                }
            
                const modalObserver = new MutationObserver(() => {
                    const isVisible = window.getComputedStyle(modal).display !== 'none';
                    if(!isVisible) {
                        releaseTrap();
                    }
                });
            
                modalObserver.observe(modal, { attributes: true, attributeFilter: ['style'] });
                modal.__observer = modalObserver;
            }, 100);
        });
    });
    
        function updateAriaHidden(heroContainer) {
            const slides = heroContainer.querySelectorAll('.swiper-slide');
            slides.forEach(slide => {
                if (slide.classList.contains('swiper-slide-active')) {
                    slide.setAttribute('aria-hidden', 'false');
                } else {
                    slide.setAttribute('aria-hidden', 'true');
                }
            });
			updateAlertImgContainer();
        }
    
        function trapFocus(container, opener) {
            const focusableSelectors = [
            'a[href]',
            'button:not([disabled])',
            'textarea:not([disabled])',
            'input:not([disabled])',
            'select:not([disabled])',
            '[tabindex]:not([tabindex="-1"])'
            ];
        
            const focusableElements = Array.from(
            container.querySelectorAll(focusableSelectors.join(','))
            );
        
            if (focusableElements.length === 0) return;
        
            const first = focusableElements[0];
            const last = focusableElements[focusableElements.length - 1];
        
            // Put initial focus inside
            first.focus();
        
            function handleKeydown(e) {
                if (e.key !== "Tab") return;
            
                if (e.shiftKey) {
                    if (document.activeElement === first) {
                        e.preventDefault();
                        last.focus();
                    }
                } else {
                    if (document.activeElement === last) {
                        e.preventDefault();
                        first.focus();
                    }
                }
            }
        
            container.addEventListener("keydown", handleKeydown);
        
            return () => {
                container.removeEventListener("keydown", handleKeydown);
                if (opener) opener.focus();
            };
        }
})
    </script>
    <?php
});

// Add visually hidden class to the web css so we can use it in components
add_action('wp_enqueue_scripts', function() {
    wp_register_style('my-accessibility', false); 
    wp_enqueue_style('my-accessibility');
    wp_add_inline_style('my-accessibility', '
        .visually-hidden {
          position: absolute !important;
          overflow: hidden;
          width: 1px;
          height: 1px;
          margin: -1px;
          padding: 0;
          border: 0;
          clip: rect(0 0 0 0);
          word-wrap: normal !important;
          background: #fff;
          color: #000;
        }
    ');
});

// Thumbnail accessibility
// Update the link label that opens an image modal and update the alt text of the image in the modal
// It requires "ally-tl" class in the item container
// It also requires "ally-modal-listener" class in the parent of this item to add focus trap
add_action('wp_footer', function() {
  ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
			const ALLY_THUMBNAIL_CLASS_ID = ".ally-tl";
			
			document.querySelectorAll(ALLY_THUMBNAIL_CLASS_ID + " a").forEach(item => {
				const img = item.querySelector("img");
				const altText = img.getAttribute("alt");
				item.setAttribute("aria-label", "View full screen: " + altText);
				item.setAttribute("role", "button");
				item.addEventListener('click', () => {
					setTimeout(() => {
						updateModal();
					}, 300);
				});
			});
			
			function updateModal() {
				const dialog = document.querySelector('.dialog-widget');
			  if (dialog) {
				  dialog.setAttribute("role", "dialog");
				  
				  //Add alt text in the modal images
				  const imgsDialog = dialog.querySelectorAll(".swiper-slide:not(.swiper-slide-duplicate) img");
				  const imgsNoDialog = document.querySelectorAll(ALLY_THUMBNAIL_CLASS_ID + " img");
				  imgsNoDialog.forEach((item, index) => {
					 const altText = item.getAttribute("alt");
					  if(imgsDialog[index]) {
						  imgsDialog[index].setAttribute("alt", altText);
					  }
				  });
				  
				  const activeImg = dialog.querySelector(".swiper-slide-active img");
				  if(activeImg) {
					activeImg.setAttribute("tabindex", "-1");
				  	activeImg.focus();
				  }
				  
			  }
			}
        });
    </script>
    <?php
});

// It requires "ally-modal-listener" class in the parent container of the items that open modals
// (IMPORTANT) If there are multiple items with "ally-tl" the "ally-modal-listener" class should be in the common parent of all these items
// It adds focus back to the opener when the modal is closed, either by the close button or by the escape key
add_action('wp_footer', function () {
    ?>
<script>
document.addEventListener('DOMContentLoaded', function () {

  let modalFound = false;

  function handleEscape(opener) {
    const onKey = (e) => {
      const modal = document.querySelector(".dialog-widget-content");
      
      if (!modal) {
        document.removeEventListener("keydown", onKey);
        return;
      }

      if (e.key === "Escape") {
        document.removeEventListener("keydown", onKey);
        setTimeout(() => opener.focus(), 700);
      }
    };

    document.addEventListener("keydown", onKey);
  }

  function attachCloseEvents(closeBtn, opener) {
    const returnFocus = () => setTimeout(() => opener.focus(), 500);

    closeBtn.addEventListener('click', returnFocus);

    closeBtn.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') returnFocus();
    });

    handleEscape(opener);
  }

  function observeModal(opener) {
    const observer = new MutationObserver(() => {
      const closeBtn = document.querySelector('.dialog-widget-content .dialog-close-button');
      if (!closeBtn) return;

      modalFound = true;
      attachCloseEvents(closeBtn, opener);
      observer.disconnect();
    });

    observer.observe(document.body, { subtree: true, childList: true });
  }

  document.querySelectorAll('.ally-modal-listener a').forEach(opener => {
    opener.addEventListener('click', () => {
      if (modalFound) {
        const closeBtn = document.querySelector('.dialog-widget-content .dialog-close-button');
        if (closeBtn) attachCloseEvents(closeBtn, opener);
      } else {
        observeModal(opener);
      }
    });
  });

});
</script>
    <?php
});


// It requires "ally-ts" class in at least 2 elements inside a container, 
// then it adds role list to the common ancestor and role listitem to the direct children that contains an element with the selector,
// and aria-hidden to the ones that don't contain any.
add_action('wp_footer', function() {
    ?>
    <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Find all ally-ls elements
        const allylsElements = Array.from(document.querySelectorAll('.ally-ls'));

        if (allylsElements.length < 2) return; // no list to create

        // 2. Helper: get element depth from root (or document.body)
        function getDepth(el) {
            let depth = 0;
            while (el && el !== document.body) {
                depth++;
                el = el.parentElement;
            }
            return depth;
        }

        // 3. Group ally-ls by depth
        const depthGroups = {};
        allylsElements.forEach(el => {
            const d = getDepth(el);
            if (!depthGroups[d]) depthGroups[d] = [];
            depthGroups[d].push(el);
        });

        // 4. Helper: find common ancestor of a set of elements
        function findCommonAncestor(elements) {
            if (!elements.length) return null;
            if (elements.length === 1) return elements[0].parentElement;

            const paths = elements.map(el => {
                const path = [];
                while (el) {
                    path.unshift(el);
                    el = el.parentElement;
                }
                return path;
            });

            let commonAncestor = null;
            for (let i = 0;; i++) {
                const first = paths[0][i];
                if (!first) break;
                if (paths.every(path => path[i] === first)) {
                    commonAncestor = first;
                } else {
                    break;
                }
            }
            return commonAncestor;
        }

        // 5. For each depth group with 2+ ally-ls, add ally-ls to all siblings at that depth under common ancestor
        for (const depth in depthGroups) {
            const group = depthGroups[depth];
            if (group.length < 2) continue;

            const ancestor = findCommonAncestor(group);
            if (!ancestor) continue;

            // Find all descendants at the same depth (relative to body)
            const allDescendants = Array.from(ancestor.querySelectorAll('*')).filter(el => getDepth(el) == depth);

            // Add ally-ls class to all at this depth inside ancestor
            allDescendants.forEach(el => el.classList.add('ally-ls'));

            // 6. Add role="list" to the common ancestor if not already set
            if (!ancestor.hasAttribute('role')) {
                ancestor.setAttribute('role', 'list');
            }

            // 7. For direct children of ancestor:
            Array.from(ancestor.children).forEach(child => {
                // If child or any descendant has ally-ls, add role listitem
                if (child.querySelector('.ally-ls') || child.classList.contains('ally-ls')) {
                    child.setAttribute('role', 'listitem');
                    child.removeAttribute('aria-hidden');
					const img = child.querySelector('.elementor-cta__bg');
					if(img) {
						img.setAttribute('alt', '');
					}
                } else {
                    child.setAttribute('aria-hidden', 'true');
                    child.removeAttribute('role');
                }
            });
        }
    });
    </script>
    <?php
});

// It requires "ally-hero-slider" class in the parent container of the hero slider
// It adds a button to pause/resume the autoplay of the slider
add_action('wp_footer', function() {
 ?>
    <script>
    // Wait for the DOM content to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {

        // Create a MutationObserver to watch for changes in the DOM
        const observer = new MutationObserver(function(mutations, obs) {
            // Look for the swiper wrapper element
            const swiperWrapper = document.querySelector('.ally-hero-slider .elementor-main-swiper .swiper-wrapper');
            
            // If the swiper wrapper is found, set up slider controls and stop observing
            if (swiperWrapper) {
                setupSliderControls();
                obs.disconnect(); // Stop observing once we have initialized the controls
            }
        });

        // Start observing the document for child and subtree changes
        observer.observe(document, {
            childList: true,
            subtree: true
        });

        // Function to set up the slider controls
        function setupSliderControls() {
            // Loop through all elements with the class 'elementor-widget-slides'
            document.querySelectorAll('.elementor-widget-slides').forEach(function($thisWidget, index) {
                // Find the main swiper element within the current widget
                var $slider = $thisWidget.querySelector('.elementor-main-swiper');
                var $swiperWrapper = $slider.querySelector('.swiper-wrapper');
                var swiperWrapperId = $swiperWrapper.getAttribute('id');

                // If the swiper wrapper does not have an ID, assign a unique ID
                if (!swiperWrapperId) {
                    swiperWrapperId = 'swiper-wrapper-' + index;
                    $swiperWrapper.setAttribute('id', swiperWrapperId);
                }
				
				const pauseSVG = `
                    <svg style="position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);" xmlns="http://www.w3.org/2000/svg" width="24" height="24"  fill="#E83416" viewBox="0 0 24 24">
                      <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
                    </svg>
                `;
                const playSVG = `
                    <svg style="position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#E83416" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z"/>
                    </svg>
                `;

                // Create a pause/resume button for the slider
                var buttonId = 'pause-button-' + index;
                var $button = document.createElement('button');
				$button.innerHTML = pauseSVG;
                $button.setAttribute('id', buttonId);
                $button.setAttribute('aria-controls', swiperWrapperId);
                $button.setAttribute('aria-label', 'Pause Slider');
                $button.setAttribute('aria-pressed', 'false');
				$button.style.borderRadius= '50%';
				$button.style.width= '60px';
				$button.style.height= '60px';
                // Insert the button before the slider in the DOM
                $slider.parentNode.insertBefore($button, $slider);

                // Add an event listener to handle the button's click event
                $button.addEventListener('click', function() {
                    // Toggle autoplay and update button text and aria-pressed attribute
                    if (this.getAttribute("aria-label") === 'Pause Slider') {
                        $slider.swiper.autoplay.stop();
                        $button.innerHTML = playSVG;
                        this.setAttribute('aria-pressed', 'true');
						this.setAttribute('aria-label', 'Play Slider');
                    } else {
                        $slider.swiper.autoplay.start();
                        $button.innerHTML = pauseSVG;
                        this.setAttribute('aria-pressed', 'false');
						this.setAttribute('aria-label', 'Pause Slider');
                    }
                });
            });
        }
    });
    </script>
    <?php
});


// It requires "ally-hero-slider-container" class in the parent container of the hero slider
// It adds aria-hidden to the non active slides and aria-label to the pagination bullets with the format "Slide X of Y", and alt text to the images with class "swiper-slide-bg"
add_action('wp_footer', function() {
?>
		<script type="text/javascript">
			const ALLY_HERO_SLIDER_CONTAINER = ".ally-hero-slider-container";
			document.addEventListener('DOMContentLoaded', () => {
				
				function updateAriaHidden(heroContainer) {
				  const slides = heroContainer.querySelectorAll('.swiper-slide');
				  slides.forEach(slide => {
					if (slide.classList.contains('swiper-slide-active')) {
					  toogleSlideAccessibility(slide, true);
					} else {
					  toogleSlideAccessibility(slide, false);
					}
				  });
				}
				
				function toogleSlideAccessibility(slide, active) {
					slide.setAttribute('aria-hidden', active ? 'false' : 'true');
					slide.querySelectorAll("a").forEach(item => {
						item.setAttribute("tabindex", active ? "0" : "-1");
					});
				}
				
				function dotsListener(heroContainer) {
					const dots = heroContainer.querySelectorAll('.swiper-pagination .swiper-pagination-bullet');
					dots.forEach((itemIn, index) => {
						setTimeout(() => {
							itemIn.setAttribute('aria-label', `Slide ${index+1} of ${dots.length}`);
						}, 300);
					})
				}
				
				const heroContainer = document.querySelector(ALLY_HERO_SLIDER_CONTAINER);
				if(!heroContainer) return;
				
				heroContainer.querySelectorAll('.swiper-slide-bg').forEach(img => img.setAttribute('alt', ''));
					
				setTimeout(() => {
					const dots = heroContainer.querySelectorAll('.swiper-pagination .swiper-pagination-bullet');
					dots.forEach((item, index) => {
						item.setAttribute('aria-label', `Slide ${index+1} of ${dots.length}`);
					});
					
					heroContainer.querySelectorAll('.swiper-pagination .swiper-pagination-bullet, '  + ALLY_HERO_SLIDER_CONTAINER+' .elementor-swiper-button').forEach(item => {
						item.addEventListener('click', () => {
							dotsListener(heroContainer);
						});
					})
				}, 500);
				
				// Run once
				updateAriaHidden(heroContainer);

				// Watch for class changes
				const observer = new MutationObserver(() => updateAriaHidden(heroContainer));
				heroContainer.querySelectorAll('.swiper-slide').forEach(slide => {
				  observer.observe(slide, { attributes: true, attributeFilter: ['class'] });
				});
			});
		</script>
	<?php
});

//It requires "ally-list-pdf" class in the parent container of the list of pdfs
// It adds list semantics to the list, adding role list to the container and role listitem to the direct children that contains a pdf link, 
// and aria-describedby to the link with the id of the titles in the section.
add_action('wp_footer', function () {
    ?>
    <script>
        (function() {
			const ALLY_LIST_PDF = ".ally-list-pdf";
			document.addEventListener('DOMContentLoaded', () => {
				const listContainer = document.querySelector(ALLY_LIST_PDF+ ' .ae-acf-repeater-widget-wrapper .ae-acf-repeater-wrapper');
				if(!listContainer) return;
				listContainer.setAttribute('role', 'list');
				listContainer.querySelectorAll(':scope > .ae-acf-repeater-item').forEach(element => {
					element.setAttribute('role', 'listitem');
				});
				
				
				
				document.querySelectorAll(ALLY_LIST_PDF + ' section .elementor-container').forEach((section, index) => {
					const ul = section.querySelector('ul')
					const pdfLink = ul.querySelector('a');
					
					ul.replaceWith(pdfLink);
					let describedByIds = '';
					[...section.querySelectorAll('.elementor-heading-title')].reverse()
					.forEach((column, indexChild) => {
						column.id = `title_${index}_${indexChild}`;
						describedByIds += column.id + ' ';
					});
					
					pdfLink.setAttribute('aria-describedby', describedByIds);
				});

			});
        })();
    </script>
    <?php
});

// It requires "change_to_h2" class in the parent container of the gravity form
// (IMPORTANT) Use this only if it's skipping heading levels in the form, otherwise it can be ignored
// It converts gravity form headings(inside the form) to h2s, by default are h3s
add_action('wp_footer', function () {
    ?>
    <script>
		const ALLY_SELECTOR = '.change_to_h2';

		function convertH3ToH2(root = document) {
		  root.querySelectorAll(`${ALLY_SELECTOR} h3`).forEach(h3 => {

			// Create new h2
			const h2 = document.createElement('h2');

			// Copy content
			h2.innerHTML = h3.innerHTML;

			// Copy attributes (optional)
			[...h3.attributes].forEach(attr => {
			  if (attr.name !== 'aria-level') {
				h2.setAttribute(attr.name, attr.value);
			  }
			});

			// Replace node
			h3.replaceWith(h2);
		  });
		}

		document.addEventListener('DOMContentLoaded', () => {

		  // Initial conversion
		  convertH3ToH2();

		  // Observe DOM changes
		  const observer = new MutationObserver(mutations => {
			for (const mutation of mutations) {
			  if (mutation.addedNodes.length > 0) {
				convertH3ToH2(mutation.target);
			  }
			}
		  });

		  observer.observe(document.body, {
			childList: true,
			subtree: true
		  });

		});

		</script>
	<?php
});

// It requires "ally-tls" class in the parent container of the timeline widget
// It adds list semantics to the timeline widget
add_action('wp_footer', function () {
    ?>
    <script>
		    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.ally-tls').forEach(function (daysWrapper) {
            const daysContainer = daysWrapper.querySelector('.uael-days');
            if (daysContainer) {
                daysContainer.setAttribute('role', 'list');
                Array.from(daysContainer.children).forEach(function (child) {
                    child.setAttribute('role', 'listitem');
                });
            }
        });
    });
		</script>
	<?php
});

// It automatically replaces the heading in the gravity form validation error with a div with the same content, 
// to avoid improper heading levels
add_action('wp_footer', function () {
    ?>
    <script>
				    new MutationObserver(() => {
      const error = document.querySelector('.gform_validation_errors');
      if(error) {
        const title = error.querySelector('h2');
        if(title) {
                  const div = document.createElement('div');
        div.className = title.className;
        div.innerText = title.innerText;
        title.replaceWith(div);
        }
      }
    }).observe(document.body, {
      childList: true,
      subtree: true
    });
		</script>
	<?php
});

// No class required, it is applied to all forms
// It clicks the focused checkbox, radio, select or submit button when the user presses enter, to improve keyboard navigation in forms.
add_action('wp_footer', function () {
	?>
		<script>
		(function () {
		  document.addEventListener('keydown', function (e) {
			if (e.key !== 'Enter') return;

			const input = e.target.closest(
			  'form input[type="checkbox"],form input[type="radio"], form select, form input[type="submit"]'
			);
			  
			  
			 const justInput = e.target.closest(
			  'form input'
			);
			  
			  if(justInput && !justInput.classList.contains('gform_next_button') && !justInput.classList.contains('gform_previous_button')) {
				  e.preventDefault();
			  }

			if (!input) return;

			e.preventDefault();
			input.click();
		  });
		})();
		</script>
	<?php
});


// No class required
// It adds accessibility attributes to the signature field in gravity forms, such as aria-roledescription and aria-description, and makes it focusable by keyboard.
//Add signature fix
add_action('wp_footer', function () {
    ?>
    <script>
		function enhanceSignatureCanvas(root = document) {
		  root
			.querySelectorAll('.gfield_signature_container.ginput_container canvas:not([data-a11y-enhanced])')
			.forEach(canvas => {
			  canvas.tabIndex = 0;
			  canvas.setAttribute('aria-roledescription', 'Signature');
			  canvas.setAttribute('aria-description', 'Sign your name');
			  canvas.setAttribute('aria-label', 'Signature*');
			  canvas.dataset.a11yEnhanced = 'true';
				
				
			  const parent = canvas.parentElement;
			  const next = parent.nextElementSibling;
				if(next) {
					
					const imgBtn = next.querySelector('img');
					if(imgBtn) {
						next.setAttribute('role', 'button');
						next.setAttribute('tabindex', '0');

						next.addEventListener('click', () => {
							imgBtn.click();
						});

						next.addEventListener('keydown', (e) => {
							if(e.key === 'Enter') {
								next.click();
							}
						});
					}

					
					
					
				}
			});
		}

		// run once for already-existing nodes
		enhanceSignatureCanvas();

		const observer = new MutationObserver(mutations => {
		  mutations.forEach(mutation => {
			mutation.addedNodes.forEach(node => {
			  if (node.nodeType !== 1) return;

			  // if the node itself is relevant
			  if (node.matches?.('.gfield_signature_container.ginput_container canvas')) {
				enhanceSignatureCanvas(node.parentElement);
			  }

			  // or if it contains relevant children
			  enhanceSignatureCanvas(node);
			});
		  });
		});

		observer.observe(document.body, {
		  childList: true,
		  subtree: true
		});
    </script>
    <?php
});

// No class required
// It adds a label to the recaptcha textarea
add_action('wp_footer', function() {
      ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
	  const observer = new MutationObserver(() => {
		document.querySelectorAll('.g-recaptcha-response').forEach(textarea => {
		  // avoid duplicating labels
		  if (textarea.dataset.hasLabel) return;

		  const id = textarea.id || `recaptcha-${Math.random().toString(36).slice(2)}`;
		  textarea.id = id;

		  const label = document.createElement('label');
		  label.setAttribute('for', id);
		  label.className = 'visually-hidden';
		  label.textContent = 'reCAPTCHA verification';

		  textarea.parentNode.insertBefore(label, textarea);

		  textarea.dataset.hasLabel = 'true';
		});
	  });

	  observer.observe(document.body, {
		childList: true,
		subtree: true
	  });
    });
    </script>
    <?php
});

// No class required
// (IMPORTANT) Remove this method if is duplicating the required asterisk in the fields, otherwise it can be ignored
// It adds an asterisk to the label of required fields in the group address field and group name field of gravity forms, 
// since these fields don't have any visible indication that they are required
add_action('wp_head', function () {

    ?>

    <script>

		function addAsteriskToRequiredAddressFields(root = document) {
		  const fields = root.querySelectorAll(
			'.ginput_container_address input, .ginput_container_address select, .ginput_complex input'
		  );

		  fields.forEach(field => {
			if (field.getAttribute('aria-required') !== 'true') return;

			const wrapper = field.parentElement;
			if (!wrapper) return;

			const label = wrapper.querySelector('label');
			if (!label) return;

			if (label.querySelector('.gfield_required')) return;

			const asterisk = document.createElement('span');
			asterisk.textContent = '*';
			asterisk.style.paddingLeft = '5px';
			asterisk.className = 'gfield_required';

			label.appendChild(asterisk);
		  });
		}

		function observeGravityForms() {
		  const form = document.querySelector('.elementor-widget-eael-gravity-form form');
			if(!form) return
			addAsteriskToRequiredAddressFields(form);

			const observer = new MutationObserver(() => {
				const newForm = document.querySelector('.elementor-widget-eael-gravity-form form:not([applied])');
				if(!newForm) return
				newForm.setAttribute('applied', 'true');
				addAsteriskToRequiredAddressFields(newForm);
			});

			observer.observe(document.body, {
			  childList: true,
			  subtree: true
			});
		}

		document.addEventListener('DOMContentLoaded', observeGravityForms);

    </script>

    <?php

});

// It requires "ally-remove-list-semantics" class in the parent container
// (IMPORTANT) Use only if the icon list shouldn't be a list like using the icon list for 1 item
// It removes the list semantics of the icon list widget
add_action('wp_head', function () {

    ?>

    <script>
		document.addEventListener('DOMContentLoaded', function () {
				  document
	    .querySelectorAll('.ally-remove-list-semantics')
	    .forEach(container => {
	      container
	        .querySelectorAll('ul.elementor-icon-list-items')
	        .forEach(ul => {
	          ul.setAttribute('role', 'none');
	
	          ul.querySelectorAll('li').forEach(li => {
	            li.setAttribute('role', 'none');
	          });
	        });
	    });
		})


    </script>

    <?php

});

//Focus title of current step in step forms
add_action('wp_footer', function () {
	?>
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				function focusFirstFieldIn(form) {
					
				  const firstField = form.querySelector('.gf_progressbar_title');
				  if (firstField) {
					  firstField.tabIndex = '-1'
					firstField.focus();
				  }
				}

				function observeFormsAndFocus() {
				  const handledForms = new WeakSet();
					const form = document.querySelector('.ally-form-focus-first form');
					if(form) {
						focusFirstFieldIn(form);
					}

				  const observer = new MutationObserver(mutations => {
					for (const mutation of mutations) {
					  for (const node of mutation.addedNodes) {
						if (!(node instanceof HTMLElement)) continue;

						// Case 1: the node itself is the form
						if (node.matches?.('.ally-form-focus-first form')) {
						  if (!handledForms.has(node)) {
							handledForms.add(node);
							focusFirstFieldIn(node);
						  }
						}

						// Case 2: form is inside the added subtree
						const forms = node.querySelectorAll?.('.ally-form-focus-first form');
						forms?.forEach(form => {
						  if (!handledForms.has(form)) {
							handledForms.add(form);
							focusFirstFieldIn(form);
						  }
						});
					  }
					}
				  });

				  observer.observe(document.body, {
					childList: true,
					subtree: true
				  });
				}

				observeFormsAndFocus();

			})
		</script>
	<?php
});

// Hide all <br> from screen readers, including dynamically added ones
add_action('wp_footer', function () {
?>
<script>
(function () {
  function hideBRs(root = document) {
    root.querySelectorAll('br:not([aria-hidden])')
      .forEach(br => br.setAttribute('aria-hidden', 'true'));
  }

  // Initial pass
  document.addEventListener('DOMContentLoaded', () => {
    hideBRs();

    // Observe future DOM changes
    const observer = new MutationObserver(mutations => {
      for (const m of mutations) {
        for (const node of m.addedNodes) {
          if (node.nodeType !== 1) continue;

          // If the node itself is <br>
          if (node.tagName === 'BR') {
            node.setAttribute('aria-hidden', 'true');
          }

          // If it contains <br> inside
          hideBRs(node);
        }
      }
    });

    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  });
})();
</script>
<?php
});
//Add role main
add_action('wp_footer', function () {
?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const main = document.querySelector('main');
  if (main) return;
	const mainAttr = document.querySelector('#content');
  mainAttr.setAttribute('role', 'main');
});
</script>
<?php
});

// Requires "ally-gallery-archive" class in the parent container
// It adds role list to the gallery grid and role listitem to the items, 
// and it traps the focus inside the fancybox modal when it's open, and returns focus to the opener when it's closed.
add_action('wp_head', function () {

    ?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    (function () {
      /* ==========================================================
			 1. Add list semantics to gallery grid
		  ========================================================== */
      function applyGalleryListSemantics() {
        document.querySelectorAll('.ally-gallery-archive .uael-img-gallery-wrap').forEach((list) => {
          list.setAttribute('role', 'list');

          list.querySelectorAll('.uael-grid-item').forEach((item) => {
            item.setAttribute('role', 'listitem');
            item.removeAttribute('aria-hidden');
            const obsItem = new MutationObserver(() => {
              if (item.getAttribute('aria-hidden')) {
                item.removeAttribute('aria-hidden');
                obsItem.disconnect();
              }
            });
            obsItem.observe(item, {
              attributes: true,
              attributesFilter: ['class'],
            });
          });
        });
      }

      /* ==========================================================
			 2. Focus trap utility
		  ========================================================== */
	function trapFocus(container) {
	  const selectors = `
		a[href],
		button:not([disabled]),
		input,
		select,
		textarea,
		[tabindex]:not([tabindex="-1"])
	  `;

	  function handleKeydown(e) {
		if (e.key !== 'Tab') return;

		const focusable = [...container.querySelectorAll(selectors)]
		  .filter(el => el.offsetParent !== null); // visible only

		if (!focusable.length) return;

		const first = focusable[0];
		const last = focusable[focusable.length - 1];
		const active = document.activeElement;

		// Predict where the browser will go next
		const goingForward = !e.shiftKey;

		if (goingForward && active === last) {
		  e.preventDefault();
		  first.focus();
		  return;
		}

		if (!goingForward && active === first) {
		  e.preventDefault();
		  last.focus();
		  return;
		}

		// 🔥 The Fancybox fix
		// If focus is about to leave the modal, pull it back
		setTimeout(() => {
		  if (!container.contains(document.activeElement)) {
			first.focus();
		  }
		}, 0);
	  }

	  document.addEventListener('keydown', handleKeydown);
	  return () => document.removeEventListener('keydown', handleKeydown);
	}
    
      /* ==========================================================
			 3. Fancybox open/close observer
		  ========================================================== */
      let lastFocusedGalleryLink = null;
      let removeTrap = null;

      function observeFancybox() {
        const observer = new MutationObserver(() => {
          const fancybox = document.querySelector('.fancybox-container');

          // OPEN
          if (fancybox && !fancybox.dataset.trapped) {
            fancybox.setAttribute('role', 'dialog');
            fancybox.setAttribute('aria-modal', 'true');
            fancybox.setAttribute('aria-label', 'Gallery');

            fancybox.querySelectorAll('.fancybox-button').forEach((btn) => {
              btn.setAttribute('aria-label', btn.getAttribute('title') + ' slide');
            });

            fancybox
              .querySelectorAll('.fancybox-button:not(.fancybox-button--arrow_right):not(.fancybox-button--arrow_left)')
              .forEach((btn) => {
                const title = btn.getAttribute('title');
                if (title) {
                  btn.setAttribute('aria-label', title);
                }
              });

            fancybox.dataset.trapped = 'true';
            setTimeout(() => {
				const text = fancybox.querySelector('.fancybox-caption');
				if(text) {
				  text.setAttribute('aria-live','polite');
				  const currentImg = fancybox.querySelector('.fancybox-slide--current img');
					currentImg.setAttribute('alt', text.textContent);
					currentImg.tabIndex = 0;
					currentImg.focus();
					removeTrap = trapFocus(fancybox);
					setTimeout(() => {
						currentImg.tabIndex = -1;
					},50);

				}
            }, 800);
          }

          // CLOSE
          setTimeout(() => {
            if (!fancybox && lastFocusedGalleryLink) {
              lastFocusedGalleryLink.focus();
              lastFocusedGalleryLink = null;
            }
          }, 500);
        });

        observer.observe(document.body, {
          childList: true,
          subtree: true,
        });
      }

      /* ==========================================================
			 4. Remember opener + re-trap after navigation (700ms)
		  ========================================================== */
      function setupGalleryInteractions() {
        document.addEventListener('click', (e) => {
          const opener = e.target.closest('.ally-gallery-archive .uael-grid-img');
          if (opener) {
            lastFocusedGalleryLink = opener;
          }

          if (e.target.closest('.fancybox-button--arrow_left') || e.target.closest('.fancybox-button--arrow_right')) {
            setTimeout(() => {
              const fancybox = document.querySelector('.fancybox-container');
              if (fancybox) {
                removeTrap?.();
                removeTrap = trapFocus(fancybox);
              }
            }, 700);
          }
        });
      }

      /* ==========================================================
			 5. Init
		  ========================================================== */
      applyGalleryListSemantics();
      observeFancybox();
      setupGalleryInteractions();
    })();
  });
</script>


    <?php

});

//Dropdown Gallery archiv fix
add_action('wp_head', function () {

    ?>

    <script>
		document.addEventListener('DOMContentLoaded', function () {
			function makeFilterDropdownAccessible() {
			  const wrapper = document.querySelector('.uael-filters-dropdown');
			  if (!wrapper) return;

			  const button = wrapper.querySelector('.uael-filters-dropdown-button');
			  const list = wrapper.querySelector('.uael-filters-dropdown-list');
			  const items = [...list.querySelectorAll('.uael-filters-dropdown-item')];

			  // Button semantics
			  button.setAttribute('role', 'button');
			  button.setAttribute('tabindex', '0');
			  button.setAttribute('aria-haspopup', 'listbox');
			  button.setAttribute('aria-expanded', 'false');
			  button.setAttribute('aria-controls', 'uael-filter-list');
				
				button.addEventListener('keydown', (e) => {
					if(e.key === 'Enter') {
						e.preventDefault();
						button.click();
					}
				})

			  // List semantics
			  list.setAttribute('role', 'listbox');
			  list.id = 'uael-filter-list';
			  list.hidden = true;

			  // Item semantics
			  items.forEach((item, index) => {
				item.setAttribute('role', 'option');
				item.setAttribute('tabindex', '-1');
				item.id = `uael-filter-option-${index}`;

				if (item.classList.contains('uael-current')) {
				  item.setAttribute('aria-selected', 'true');
				  button.textContent = item.textContent;
				}
			  });


				
		function openList() {
			list.hidden = false;
			button.setAttribute('aria-expanded', 'true');

			const selected =
			  list.querySelector('[aria-selected="true"]') || items[0];

			selected.focus();
		  }

		  function closeList() {
			list.hidden = true;
			button.setAttribute('aria-expanded', 'false');
			  document.body.click();
			button.focus();
		
		  }
			
		button.addEventListener('click', () => {
			const expanded = button.getAttribute('aria-expanded') === 'true';
			expanded ? closeList() : openList();
		  });

		  button.addEventListener('keydown', (e) => {
			if (e.key === 'Enter' || e.key === ' ') {
			  e.preventDefault();
			  openList();
			}
			if (e.key === 'ArrowDown') {
			  e.preventDefault();
			  openList();
			}
		  });
			
			  list.addEventListener('keydown', (e) => {
			const currentIndex = items.indexOf(document.activeElement);

			if (e.key === 'ArrowDown') {
			  e.preventDefault();
			  items[(currentIndex + 1) % items.length].focus();
			}

			if (e.key === 'ArrowUp') {
			  e.preventDefault();
			  items[(currentIndex - 1 + items.length) % items.length].focus();
			}

			if (e.key === 'Enter' || e.key === ' ') {
			  e.preventDefault();
			  document.activeElement.click();
			}

			if (e.key === 'Escape') {
			  e.preventDefault();
			  closeList();
			}
		  });


			  items.forEach(item => {
			item.addEventListener('click', () => {
			  items.forEach(i => i.removeAttribute('aria-selected'));
			  item.setAttribute('aria-selected', 'true');

			  button.textContent = item.textContent;
			  closeList();
			});
		  });

		  // Click outside closes
		  document.addEventListener('click', (e) => {
			if (!wrapper.contains(e.target)) {
			  closeList();
			}
		  });
		}

	makeFilterDropdownAccessible();
								  		})
    </script>

    <?php

});

// It requires "remove-aria-labelledby" class in the parent container of the menu widget
// It removes the aria-labelledby attribute of the menu wrapper
add_action('wp_footer', function () {
    ?>
    <script>
        (function() {
			document.addEventListener('DOMContentLoaded', () => {
				const element = document.querySelector('.remove-aria-labelledby .e-n-menu-wrapper');
				if(!element) return;
				element.removeAttribute('aria-labelledby');
			});
        })();
    </script>
    <?php
});

// It requires "ally-extra-description" class in the parent container of the item, 
// "ally-heading" class in the heading of the item and 
// "ally-button" class in the link/button of the item
add_action('wp_footer', function() {
    ?>
    <script>
	  document.querySelectorAll('.ally-extra-description').forEach(container => {
			container.querySelectorAll('h3.elementor-heading-title, .ally-heading .elementor-heading-title').forEach(title => {
				title.id = 'title_id_' + Math.random().toString(36).substring(2, 6 + 2);
				const parentItem = title.closest('.has_ae_slider');
				if(!parentItem)return;
				
				const btn = parentItem.querySelector('.elementor-button, .ally-button a');
				btn.setAttribute('aria-describedby', title.id);
			})
	  });
    </script>
    <?php
});

// It requires "ally-iframe_" class in the parent container of the iframe, 
// and after the "_" it will split the text by underscore and use it as aria-label for the iframe, replacing underscores with spaces and capitalizing the first letter of each word.
// Example: ally-iframe_facebook_photos here "facebook photos" will be the aria-label
function label_iframe_with_class() {
    ?>
    <script>
		(function () {
		  function labelAllyIframes() {
			document.querySelectorAll('[class*="ally-iframe_"]').forEach(wrapper => {
			  const cls = [...wrapper.classList].find(c => c.startsWith('ally-iframe_'));
			  if (!cls) return;

			  const iframe = wrapper.querySelector('iframe');
			  if (!iframe || iframe.dataset.ariaLabeled) return;

			  const rawName = cls.replace('ally-iframe_', '');
			  const label = rawName
				.replace(/_/g, ' ')
				.replace(/\b\w/g, l => l.toUpperCase());

			  iframe.setAttribute('aria-label', label);
			  iframe.dataset.ariaLabeled = 'true';
			});
		  }

		  // run once initially
		  labelAllyIframes();

		  // observe dynamic inserts
		  const observer = new MutationObserver(labelAllyIframes);
		  observer.observe(document.body, {
			childList: true,
			subtree: true
		  });
		})();
    </script>
    <?php
}
add_action('wp_footer', 'label_iframe_with_class');

//It requires ally-list-post-collection class
//It adds list semantics and remove inner unnecessary listitem
add_action('wp_footer', function () {
    ?>
		<script>
			document.addEventListener('DOMContentLoaded', () => {
			  document.querySelectorAll('.ally-list-post-collection').forEach(wrapper => {
				const collection = wrapper.querySelector('.ae-post-collection, .ae-post-list-wrapper');
				 wrapper.removeAttribute("role");
				if (!collection) return;

				collection.setAttribute('role', 'list');

				collection.querySelectorAll(':scope > article').forEach(article => {
				  article.setAttribute('role', 'listitem');
					article.querySelectorAll('[role="listitem"]').forEach(item => {
						item.removeAttribute('role');
					});
				});
			  });
			});
		</script>
	<?php
});

//ally-remove-title required class to remove title from elements with the title attribute
add_action('wp_footer', function () {
    ?>
		<script>
			document.addEventListener('DOMContentLoaded', () => {
			  document.querySelectorAll('.ally-remove-title [title]').forEach(element => {
				element.removeAttribute('title');
			  });
			});
		</script>
	<?php
});

//for input file elements it clicks the element on Enter key
add_action('wp_footer', function () {
    ?>
		<script>
			document.addEventListener('keydown', (event) => {
			  if (event.key !== 'Enter') return;

			  const el = document.activeElement;

			  if (el?.matches('input[type="file"]')) {
				event.preventDefault();
				el.click();
			  }
			});

		</script>
	<?php
});

//Function to replace nav tag and remove aria-label form footer nav
add_action('wp_footer', function () {
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const containers = document.querySelectorAll('.ally-extra-footer-nav');

            containers.forEach(container => {
                const oldNav = container.querySelector('nav.e-n-menu');
                
                if (oldNav) {
                    const newDiv = document.createElement('div');

                    Array.from(oldNav.attributes).forEach(attr => {
                        if (attr.name.toLowerCase() !== 'aria-label') {
                            newDiv.setAttribute(attr.name, attr.value);
                        }
                    });

                    while (oldNav.firstChild) {
                        newDiv.appendChild(oldNav.firstChild);
                    }

                    oldNav.parentNode.replaceChild(newDiv, oldNav);
                }
            });
        });
    </script>
    <?php
}, 100);

//update header nav aria-label
add_action('wp_footer', function() {
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nav = document.querySelector('.ada-header-nav nav');
            if (!nav) return;
            nav.setAttribute('aria-label', 'Primary');
        });
    </script>
    <?php
});

// Remove certain items from the page sitemap
add_action('wp_footer', function () {
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.querySelector('.ada-sitemap');
            if (!container) return;

            setTimeout(function() {
				//Here will be the exact visual text of the links to be removed in the sitemap
                const termsToRemove = [
                    'Key Box Inquiry Received',
                ];

                const sitemapLinks = container.querySelectorAll('.elementor-sitemap-list a');

                sitemapLinks.forEach(function (link) {
                    const text = link.textContent.trim();
                    
                    if (termsToRemove.includes(text)) {
                        const listItem = link.closest('li');
                        if (listItem) {
                            listItem.remove();
                        }
                    }
                });
            }, 500);
        });
    </script>
    <?php
});
