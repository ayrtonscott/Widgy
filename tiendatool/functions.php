<?php
  // Escape special chars to prevent SQL injection
  function esc($payload) { 
    return htmlspecialchars($payload, ENT_QUOTES, 'UTF-8'); 
  }