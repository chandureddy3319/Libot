document.addEventListener('DOMContentLoaded', function() {
  const chatBtn = document.createElement('button');
  chatBtn.className = 'btn btn-primary rounded-circle';
  chatBtn.style.position = 'fixed';
  chatBtn.style.bottom = '30px';
  chatBtn.style.right = '30px';
  chatBtn.style.zIndex = 9999;
  chatBtn.innerHTML = '<span class="bi bi-chat-dots" style="font-size:1.5em;"></span>';
  document.body.appendChild(chatBtn);

  const chatBox = document.createElement('div');
  chatBox.style.position = 'fixed';
  chatBox.style.bottom = '80px';
  chatBox.style.right = '30px';
  chatBox.style.width = '320px';
  chatBox.style.maxWidth = '90vw';
  chatBox.style.background = '#fff';
  chatBox.style.border = '1px solid #ccc';
  chatBox.style.borderRadius = '10px';
  chatBox.style.boxShadow = '0 2px 8px rgba(0,0,0,0.2)';
  chatBox.style.display = 'none';
  chatBox.style.zIndex = 10000;
  chatBox.innerHTML = `
    <div class='p-2 bg-primary text-white rounded-top'>Library Chatbot <button class='btn-close btn-close-white float-end' id='close-chat'></button></div>
    <div id='chat-messages' style='height:200px;overflow-y:auto;padding:10px;'></div>
    <form id='chat-form' class='d-flex border-top'>
      <input type='text' class='form-control border-0' id='chat-input' placeholder='Ask me anything...'>
      <button class='btn btn-primary' type='submit'>Send</button>
    </form>
  `;
  document.body.appendChild(chatBox);

  chatBtn.onclick = () => chatBox.style.display = chatBox.style.display === 'none' ? 'block' : 'none';
  chatBox.querySelector('#close-chat').onclick = () => chatBox.style.display = 'none';

  const chatMessages = chatBox.querySelector('#chat-messages');
  const chatForm = chatBox.querySelector('#chat-form');
  const chatInput = chatBox.querySelector('#chat-input');

  function botReply(msg) {
    let reply = 'Sorry, I did not understand. Try: "How to issue a book?", "How to return?", "What is fine?"';
    msg = msg.toLowerCase();
    if (msg.includes('issue')) reply = 'To issue a book, add it to your cart and checkout. Wait for admin approval.';
    else if (msg.includes('return')) reply = 'Go to My Issued Books and click Return. Admin can also mark as returned.';
    else if (msg.includes('fine')) reply = 'Fine is ₹10 per day after the due date. Check your issued books for details.';
    else if (msg.includes('reserve')) reply = 'If a book is not available, click Reserve to join the queue.';
    else if (msg.includes('login')) reply = 'Use your email and password to login. Admin uses the default credentials.';
    else if (msg.includes('register')) reply = 'Register with your email, username, USN, and department.';
    else if (msg.includes('dark')) reply = 'Click the moon icon in the navbar to toggle dark mode.';
    chatMessages.innerHTML += `<div class='text-end mb-2'><span class='badge bg-primary'>${reply}</span></div>`;
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }
  chatForm.onsubmit = function(e) {
    e.preventDefault();
    const msg = chatInput.value.trim();
    if (!msg) return;
    chatMessages.innerHTML += `<div class='mb-2'><span class='badge bg-secondary'>${msg}</span></div>`;
    chatInput.value = '';
    botReply(msg);
  };
}); 