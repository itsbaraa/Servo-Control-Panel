document.addEventListener('DOMContentLoaded', () => {
    const sliders = document.querySelectorAll('input[type="range"]');

    // Initialize all sliders on page load
    sliders.forEach(slider => {
        updateSliderFill(slider);
        updateSliderValue(slider.id); // Also update the text value
    });

    // Add event listeners to update on change
    sliders.forEach(slider => {
        slider.addEventListener('input', () => {
            updateSliderFill(slider);
            updateSliderValue(slider.id); // Also update the text value
        });
    });
});

// Function to update the visual fill of a slider
function updateSliderFill(slider) {
    const min = slider.min || 0;
    const max = slider.max || 180;
    const value = slider.value;
    const percentage = ((value - min) / (max - min)) * 100;
    
    slider.style.background = `linear-gradient(to right, #0078d4 ${percentage}%, #ddd ${percentage}%)`;
}

// Function to update the displayed text value of a slider
function updateSliderValue(sliderId) {
    const slider = document.getElementById(sliderId);
    const display = document.getElementById(sliderId + '_val');
    if (display) {
        display.textContent = slider.value;
    }
}