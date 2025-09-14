/**
 * Smooth scroll to an element's ID with an offset
 * @param {string} targetId - The ID of the target element
 * @param {number} duration - The duration of the animation in milliseconds
 * @param {number} offset - The offset in pixels (default: 0)
 */
function smoothScrollToId(targetId, duration, offset = 0) {
  const targetElement = document.getElementById(targetId);
  if (!targetElement) {
    console.error(`Element with ID "${targetId}" not found.`);
    return;
  }

  const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - offset;
  const startPosition = window.pageYOffset;
  const distance = targetPosition - startPosition;
  let startTime = null;

  function easeInOutCubic(t) {
    return t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1;
  }

  function animation(currentTime) {
    if (startTime === null) startTime = currentTime;
    const timeElapsed = currentTime - startTime;
    const run = easeInOutCubic(Math.min(timeElapsed / duration, 1));
    window.scrollTo(0, startPosition + distance * run);
    if (timeElapsed < duration) requestAnimationFrame(animation);
  }

  requestAnimationFrame(animation);
}