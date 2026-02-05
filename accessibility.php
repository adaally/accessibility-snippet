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

//.ally-accordion is required as the parent
add_action('wp_footer', function () {
    ?>
    <script>
document.addEventListener('DOMContentLoaded', () => {
	  document.querySelectorAll('.ally-accordion .e-n-accordion').forEach(acc => {
	acc.removeAttribute('aria-label');
	acc.querySelectorAll('details').forEach(item => {
		item.querySelectorAll('[role="region"]').forEach(element => {
			element.removeAttribute('role');
			element.removeAttribute('aria-labelledby');
		});
		
		const div = document.createElement('div');
		div.className = item.className;
		div.appendChild(item);
		acc.appendChild(div);
	});
  
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


// Convert gravity form headings to h2
// Cascading function that uses ally-gh2 as the selector


add_action('wp_footer', function () {
    ?>
    <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        // Find all h3 elements with class "gsection_title" inside any ancestor with class "ally-gh2"
        const elements = document.querySelectorAll('.ally-gh2 .gsection_title');

        elements.forEach(el => {
            if (el.tagName.toLowerCase() === 'h3') {
                const newEl = document.createElement('h2');
                newEl.className = el.className;
                newEl.innerHTML = el.innerHTML;
                el.replaceWith(newEl);
            }
        });
    });
    </script>
    <?php

});

// Add list semantics to galleries
// Cascading function that uses ally-gls as the selector
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
// Cascading function that uses ally-tl as the selector
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
				  console.log(activeImg)
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

//Add list semantics to visual lists
//Fountain function that uses ally-ls as the selector; At least two elements in the list must have the selector. 

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


//Add list semantics too accordions
//Cascading function that uses ally-als as the selector

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

//Slideshow Play Pause Button that contains 'elementor-main-swiper'(IMPORTANT)

add_action('wp_footer', function() {
 ?>
    <script>
    // Wait for the DOM content to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {

        // Create a MutationObserver to watch for changes in the DOM
        const observer = new MutationObserver(function(mutations, obs) {
            // Look for the swiper wrapper element
            const swiperWrapper = document.querySelector('.elementor-main-swiper .swiper-wrapper');
            
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

// Thumbnail accessibility
// Add ally-hero-slider-container class to the container of the hero slider in order to work

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


add_action('wp_footer', function () {
    ?>
    <script>
        (function() {
				const ALLY_NESTED_LIST = ".ally-nested-list";
			document.addEventListener('DOMContentLoaded', () => {
				let globalList = null;
				document.querySelectorAll(ALLY_NESTED_LIST + ' section').forEach((section, index) => {
					if(index === 0) {
						globalList = section.querySelector('ul');
						
						section.querySelectorAll('.elementor-column').forEach((column, index) => {
							if(index !== 0) {
								column.remove();
							}
						})
					} else {
						section.querySelectorAll('li').forEach(item => globalList.appendChild(item));
						section.remove();
					}
					
				});

			});
        })();
    </script>
    <?php
});

add_action('wp_footer', function () {
    ?>
    <script>
        (function() {
const ALLY_LIST_EMPLOYMENT = ".ally-list-employment";
			document.addEventListener('DOMContentLoaded', () => {
				document.querySelectorAll(ALLY_LIST_EMPLOYMENT + ' article').forEach((item, index) => {
					if(index === 0){
						const parent = item.parentNode;
						parent.setAttribute('role', 'list');
					}
					item.setAttribute('role', 'listitem');
					
					
					const ulList = item.querySelector('ul');
					if(ulList) {
						ulList.setAttribute('role', 'none');
						ulList.querySelectorAll('li').forEach(item => item.setAttribute('role', 'none'));
					}
					
					const h4Element = item.querySelector('h4');
					if(h4Element) {
						h4Element.setAttribute('role', 'none');
					}
					
					const links = item.querySelectorAll('a');
					if(links.length > 1) {
						links[0].id = 'link_employment_' + index;
						links[links.length - 1].setAttribute('aria-describedby', links[0].id);
						replaceLinkWithSpan(links[0], links[links.length - 1]);
					}
					
				});
				
				function copyAttributes(source, target) {
					if (!source || !target) return;

					for (let attr of source.attributes) {
						target.setAttribute(attr.name, attr.value);
					}
				}
				
				 function replaceLinkWithSpan(link, secondLink) {
					const newSpan = document.createElement('span');
					copyAttributes(link, newSpan);
					newSpan.removeAttribute('href');
					newSpan.removeAttribute('target');
					newSpan.style.cursor = 'pointer';
					newSpan.innerHTML = link.innerHTML;
					link.replaceWith(newSpan);
					newSpan.addEventListener('click', () => {
						secondLink.click();
					});
					return newSpan;
				}

			});
        })();
    </script>
    <?php
});

	
add_action('wp_footer', function () {
    ?>
    <script>
		const ALLY_H2_CLASS = ".change_to_h2";
			document.addEventListener('DOMContentLoaded', () => {
				document.querySelectorAll(ALLY_H2_CLASS + ' h3').forEach((h3Element) => {
					h3Element.setAttribute('aria-level', '2');
				});
				const form = document.querySelector('.ally-form form');
				if (form) {
				  const obs = new MutationObserver((mutations) => {
					for (const m of mutations) {
					  if (m.type === 'attributes') {
						let intervalId;

						const timeoutId = setTimeout(() => {
						  clearInterval(intervalId);
						}, 5000);

						intervalId = setInterval(() => {
						  const h3s = document.querySelectorAll('form h3');
						  if (h3s.length > 0 && h3s[0].getAttribute('aria-level') === null) {
							h3s.forEach(h3 => {
							  h3.setAttribute('aria-level', '2');
							});
							clearInterval(intervalId);
							clearTimeout(timeoutId);
						  }
						}, 200);
					  }
					}
				  });
				  
				  obs.observe(form, {
					attributes: true
				  });
				}
			});
		</script>
	<?php
});
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

//Button open modal focus back when modal closed
//Class required on the container of images: ally-modal-listener
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

//Gravity form prevent enter key
add_action('wp_footer', function () {
	?>
		<script>
		(function () {
		  document.addEventListener('keydown', function (e) {
			if (e.key !== 'Enter') return;

			const input = e.target.closest(
			  'form input[type="checkbox"],form input[type="radio"], form select'
			);

			if (!input) return;

			e.preventDefault();
			input.click();
		  });
		})();
		</script>
	<?php
});


//Remove tabindex from link
add_action('wp_footer', function () {
    ?>
    <script>
		    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.remove-tabindex').forEach(function (wrapper) {
            const link = wrapper.querySelector('a');
            if (link) {
                link.setAttribute('tabindex', '-1');
            }
        });
    });
		</script>
	<?php
});

//Adding list semantics to accordions -> (ally-list-accordion) class to the parent
add_action('wp_footer', function () {
    ?>
		<script>
			document.addEventListener('DOMContentLoaded', () => {
			  document.querySelectorAll('.ally-list-accordion').forEach(wrapper => {
				const collection = wrapper.querySelector('.ae-post-collection');
				if (!collection) return;

				collection.setAttribute('role', 'list');

				collection.querySelectorAll(':scope > article').forEach(article => {
				  article.setAttribute('role', 'listitem');
				});
			  });
			});
		</script>
	<?php
});

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
			  canvas.dataset.a11yEnhanced = 'true';
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

//Add label to captcha
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

//add_extra_description_to_firetrucks_info_btn
add_action('wp_footer', function() {
    ?>
    <script>
	  document.querySelectorAll('.ally-add-extra-desc-to-btns').forEach(container => {
			container.querySelectorAll('.elementor-heading-title').forEach(title => {
				title.id = 'title_id_' + Math.random().toString(36).substring(2, 6 + 2);
				const parentItem = title.closest('.has_ae_slider');
				if(!parentItem)return;
				
				const summary = parentItem.querySelector('summary');
				summary.setAttribute('aria-describedby', title.id);
			})
	  });
    </script>
    <?php
});

//Add asterisks to required inputs in group of address
add_action('wp_head', function () {

    ?>

    <script>

      document.addEventListener('DOMContentLoaded', function () {

		function addAsteriskToRequiredAddressFields() {
			//Look for gravity grouped items
		  const fields = document.querySelectorAll(
			'.ginput_container_address input, .ginput_container_address select, .ginput_complex input'
		  );
			
		  fields.forEach(field => {
			if (field.getAttribute('aria-required') !== 'true') return;

			// Find the closest container that holds the label
			const wrapper = field.parentElement;
			if (!wrapper) return;

			const label = wrapper.querySelector('label');
			if (!label) return;

			// Avoid duplicating the asterisk
			if (label.querySelector('.gfield_required')) return;

			const asterisk = document.createElement('span');
			asterisk.textContent = '*';
			  asterisk.style.paddingLeft = '5px'
			asterisk.className = 'gfield_required';

			label.appendChild(asterisk);
		  });
		}
		addAsteriskToRequiredAddressFields();

	  })
    </script>

    <?php

});
