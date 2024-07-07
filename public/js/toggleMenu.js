"use strict";
window.addEventListener("DOMContentLoaded", () => {
  const LeMenu = document.getElementById("LeMenu");
  const CmdMenu = document.getElementById("CmdMenu");

  const toggleMenuDisplay = () => {
    const ww = window.innerWidth;
    LeMenu.style.display = ww > 768 ? '' : 'none';
    CmdMenu.style.display = ww > 768 ? 'none' : '';
  };

  CmdMenu.addEventListener('click', () => {
    LeMenu.style.display = LeMenu.style.display === 'none' ? '' : 'none';
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  window.addEventListener('resize', toggleMenuDisplay);

  toggleMenuDisplay();
});
