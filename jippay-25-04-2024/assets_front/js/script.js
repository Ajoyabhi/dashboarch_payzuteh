 //	window.addEventListener("resize", function() {
        //		"use strict"; window.location.reload(); 
        //	});


        document.addEventListener("DOMContentLoaded", function () {


            /////// Prevent closing from click inside dropdown
            document.querySelectorAll('.dropdown-menu').forEach(function (element) {
                element.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            })



            // make it as accordion for smaller screens
            if (window.innerWidth < 992) {

                // close all inner dropdowns when parent is closed
                document.querySelectorAll('.navbar .dropdown').forEach(function (everydropdown) {
                    everydropdown.addEventListener('hidden.bs.dropdown', function () {
                        // after dropdown is hidden, then find all submenus
                        this.querySelectorAll('.submenu').forEach(function (everysubmenu) {
                            // hide every submenu as well
                            everysubmenu.style.display = 'none';
                        });
                    })
                });

                document.querySelectorAll('.dropdown-menu a').forEach(function (element) {
                    element.addEventListener('click', function (e) {

                        let nextEl = this.nextElementSibling;
                        if (nextEl && nextEl.classList.contains('submenu')) {
                            // prevent opening link if link needs to open dropdown
                            e.preventDefault();
                            console.log(nextEl);
                            if (nextEl.style.display == 'block') {
                                nextEl.style.display = 'none';
                            } else {
                                nextEl.style.display = 'block';
                            }

                        }
                    });
                })
            }
            // end if innerWidth

        });
            // DOMContentLoaded  end



            $(window).on("scroll", function () {
                if ($(window).scrollTop() > 50) {
                    $(".navbar").addClass("active");
                    $('.navbar-brand').css('color', "#fff")
                } else {
                    //remove the background property so it comes transparent again (defined in your css)
                    $(".navbar").removeClass("active");
                    $('.navbar-brand').css('color', "#000")
                }
            });



            $(document).ready(function () {
                $('.customer-logos').slick({
                    slidesToShow: 5,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 1500,
                    arrows: false,
                    dots: false,
                    pauseOnHover: false,
                    responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 4
                        }
                    }, {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 520,
                        settings: {
                            slidesToShow: 1
                        }
                    }]
                });
            });
    
    // login script

    // const themes = [
    //     {
    //         background: "#1A1A2E",
    //         color: "#FFFFFF",
    //         primaryColor: "#0F3460"
    //     },
    //     {
    //         background: "#461220",
    //         color: "#FFFFFF",
    //         primaryColor: "#E94560"
    //     },
    //     {
    //         background: "#192A51",
    //         color: "#FFFFFF",
    //         primaryColor: "#967AA1"
    //     },
    //     {
    //         background: "#F7B267",
    //         color: "#000000",
    //         primaryColor: "#F4845F"
    //     },
    //     {
    //         background: "#F25F5C",
    //         color: "#000000",
    //         primaryColor: "#642B36"
    //     },
    //     {
    //         background: "#231F20",
    //         color: "#FFF",
    //         primaryColor: "#BB4430"
    //     }
    // ];
    
    // const setTheme = (theme) => {
    //     const root = document.querySelector(":root");
    //     root.style.setProperty("--background", theme.background);
    //     root.style.setProperty("--color", theme.color);
    //     root.style.setProperty("--primary-color", theme.primaryColor);
    //     root.style.setProperty("--glass-color", theme.glassColor);
    // };
    
    // const displayThemeButtons = () => {
    //     const btnContainer = document.querySelector(".theme-btn-container");
    //     themes.forEach((theme) => {
    //         const div = document.createElement("div");
    //         div.className = "theme-btn";
    //         div.style.cssText = `background: ${theme.background}; width: 25px; height: 25px`;
    //         btnContainer.appendChild(div);
    //         div.addEventListener("click", () => setTheme(theme));
    //     });
    // };
    
    // displayThemeButtons();