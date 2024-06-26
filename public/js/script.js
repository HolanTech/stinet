document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("#paymentForm");
    const resultBox = document.querySelector("#resultBox");
    const resultContent = document.querySelector("#resultContent");
    const placeholderImage = document.querySelector("#placeholderImage");
    const customerName = document.querySelector("#customerName");
    const customerNumber = document.querySelector("#customerNumber");
    const billingAmount = document.querySelector("#billingAmount");

    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(form);
            const nomorTelepon = formData.get("nomorTelepon");

            fetch("/cek-tagihan", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({ nomorTelepon: nomorTelepon }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        customerName.textContent = data.customerName;
                        customerNumber.textContent = data.customerNumber;
                        billingAmount.textContent = data.billingAmount;

                        placeholderImage.style.display = "none";
                        resultContent.style.display = "block";
                    } else {
                        alert(data.message);
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        });
    }

    const loginForm = document.querySelector("#loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(loginForm);
            const email = formData.get("email");
            const password = formData.get("password");

            fetch("/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({ email: email, password: password }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        swal({
                            title: "Login Successful",
                            text: "You are being redirected...",
                            icon: "success",
                            buttons: false,
                            timer: 2000,
                        }).then(() => {
                            window.location.href = data.redirect;
                        });
                    } else {
                        swal("Error", data.message, "error");
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    swal(
                        "Error",
                        "An error occurred. Please try again.",
                        "error"
                    );
                });
        });
    }

    const registerForm = document.querySelector("#registerForm");
    if (registerForm) {
        registerForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(registerForm);
            const name = formData.get("name");
            const email = formData.get("email");
            const phone = formData.get("phone");
            const alamat = formData.get("alamat");
            const password = formData.get("password");
            const password_confirmation = formData.get("password_confirmation");

            fetch("/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    phone: phone,
                    alamat: alamat,
                    password: password,
                    password_confirmation: password_confirmation,
                }),
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        swal({
                            title: "Registration Successful",
                            text: "You are being redirected...",
                            icon: "success",
                            buttons: false,
                            timer: 2000,
                        }).then(() => {
                            window.location.href = data.redirect;
                        });
                    } else {
                        swal("Error", data.message, "error");
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    swal(
                        "Error",
                        "An error occurred. Please try again.",
                        "error"
                    );
                });
        });
    }

    const testimonials = document.querySelectorAll(".testimonial-item");
    let currentIndex = 0;

    function showNextTestimonial() {
        testimonials[currentIndex].style.display = "none";
        currentIndex = (currentIndex + 1) % testimonials.length;
        testimonials[currentIndex].style.display = "block";
    }

    testimonials.forEach((testimonial, index) => {
        testimonial.style.display = index === 0 ? "block" : "none";
    });

    setInterval(showNextTestimonial, 3000);

    window.addEventListener("scroll", function () {
        const header = document.querySelector("header");
        const navLinks = document.querySelectorAll(".nav-link");
        if (window.scrollY > 50) {
            header.classList.add("scrolled");
            navLinks.forEach((link) => link.classList.add("scrolled"));
        } else {
            header.classList.remove("scrolled");
            navLinks.forEach((link) => link.classList.remove("scrolled"));
        }
    });

    const serviceItems = document.querySelectorAll(".service-item");
    const prevService = document.getElementById("prevService");
    const nextService = document.getElementById("nextService");
    let serviceIndex = 0;

    function showService(index) {
        const itemsToShow = window.innerWidth < 768 ? 1 : 2;
        serviceItems.forEach((item, i) => {
            item.style.display =
                i >= index * itemsToShow && i < (index + 1) * itemsToShow
                    ? "block"
                    : "none";
        });
    }

    prevService.addEventListener("click", function () {
        serviceIndex =
            serviceIndex === 0
                ? Math.ceil(serviceItems.length / 2) - 1
                : serviceIndex - 1;
        showService(serviceIndex);
    });

    nextService.addEventListener("click", function () {
        serviceIndex =
            serviceIndex === Math.ceil(serviceItems.length / 2) - 1
                ? 0
                : serviceIndex + 1;
        showService(serviceIndex);
    });

    showService(serviceIndex);

    // Initialize swiper for mobile view
    const serviceCarousel = document.querySelector(".service-carousel");
    let isDown = false;
    let startX;
    let scrollLeft;

    serviceCarousel.addEventListener("mousedown", (e) => {
        isDown = true;
        serviceCarousel.classList.add("active");
        startX = e.pageX - serviceCarousel.offsetLeft;
        scrollLeft = serviceCarousel.scrollLeft;
    });

    serviceCarousel.addEventListener("mouseleave", () => {
        isDown = false;
        serviceCarousel.classList.remove("active");
    });

    serviceCarousel.addEventListener("mouseup", () => {
        isDown = false;
        serviceCarousel.classList.remove("active");
    });

    serviceCarousel.addEventListener("mousemove", (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - serviceCarousel.offsetLeft;
        const walk = (x - startX) * 3; //scroll-fast
        serviceCarousel.scrollLeft = scrollLeft - walk;
    });
});
