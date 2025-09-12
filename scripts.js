document.addEventListener("DOMContentLoaded", () => {
    const sliders = document.querySelectorAll(".slider");

    sliders.forEach(slider => {
        const slides = slider.querySelectorAll(".slide");
        let index = 0;

        function showSlide(i) {
            slides.forEach((s, idx) => {
                s.style.display = (idx === i) ? "block" : "none";
            });
        }

        const prevBtn = slider.querySelector(".prev");
        const nextBtn = slider.querySelector(".next");

        if(prevBtn) prevBtn.addEventListener("click", () => {
            index = (index - 1 + slides.length) % slides.length;
            showSlide(index);
        });
        if(nextBtn) nextBtn.addEventListener("click", () => {
            index = (index + 1) % slides.length;
            showSlide(index);
        });

        setInterval(() => {
            index = (index + 1) % slides.length;
            showSlide(index);
        }, 5000);

        showSlide(index);
    });
});
