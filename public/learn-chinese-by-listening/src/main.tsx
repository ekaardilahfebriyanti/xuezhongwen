import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import './index.css'
import App from './App.tsx'

function updateBodyClass() {
  const width = window.screen.availWidth;
  const body = document.body;

  // Remove all size-related classes
  body.classList.remove("xs", "sm", "md", "lg", "lgUp", "mdUp", "smUp");

  // Apply classes based on width (mobile-first)
  if (width > 992) body.classList.add("lg");
  else if (width > 768) body.classList.add("md");
  else if (width > 576) body.classList.add("sm");
  else body.classList.add("xs");

  if (width > 992) body.classList.add("lgUp");
  if (width > 768) body.classList.add("mdUp");
  if (width > 576) body.classList.add("smUp");
}

// Run on load and resize
window.addEventListener("resize", updateBodyClass);
updateBodyClass();

createRoot(document.getElementById('root')!).render(
  <StrictMode>
    <App />
  </StrictMode>,
)
