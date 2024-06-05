function confirmOrder(productId) {
    const cupSize = document.querySelector(`input[name="cup-size-${productId}"]:checked`);
    if (cupSize) {
        const productName = document.getElementById(`${productId}`).querySelector('h2').innerText;
        const selectedSize = cupSize.value;
        const confirmationMessage = `You have ordered a ${selectedSize} cup of ${productName}.`;
        document.getElementById('confirmation-message').innerText = confirmationMessage;
        // Display popup
        document.getElementById('confirmation-modal').style.display = 'block';
        // Show overlay
        document.querySelector('.overlay').classList.add('show');
    } else {
        alert('Please choose a cup size before confirming your order.');
    }
}

function closeModal() {
    // Hide popup
    document.getElementById('confirmation-modal').style.display = 'none';
    // Hide overlay
    document.querySelector('.overlay').classList.remove('show');
}
document.getElementById("menu-icon").addEventListener("click", function(){
    document.querySelector(".navbar").classList.toggle("active");
    });

    let popup = document.getElementById("popup"); 
    function OpenPopup(){ 
        popup.classList.add("open-popup")
    }
    function closePopup(){
        popup.classList.remove("open-popup")
    }
