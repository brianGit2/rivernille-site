/**
 * Image Modal Handler
 * Manages opening, closing, and navigation of image modals
 */

class ImageModal {
  constructor() {
    this.modal = document.getElementById('image-modal');
    if (!this.modal) {
      console.error('Image modal element not found');
      return;
    }

    this.backdrop = document.querySelector('.image-modal__backdrop');
    this.closeBtn = document.querySelector('.image-modal__close');
    this.prevBtn = document.querySelector('.image-modal__prev');
    this.nextBtn = document.querySelector('.image-modal__next');
    this.imgElement = document.querySelector('.image-modal__img');
    this.captionElement = document.querySelector('.image-modal__caption');
    this.downloadBtn = document.querySelector('.image-modal__download');

    this.imageLinks = [];
    this.currentIndex = 0;
    this.isMobile = false;

    this.init();
  }

  init() {
    this.detectMobile();

    // Event listeners for closing
    if (this.closeBtn) {
      this.closeBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        this.close();
      });
    }

    if (this.backdrop) {
      this.backdrop.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        this.close();
      });
    }

    // Event listeners for navigation
    if (this.prevBtn) {
      this.prevBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        this.previous();
      });
    }

    if (this.nextBtn) {
      this.nextBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        this.next();
      });
    }

    // Keyboard navigation
    document.addEventListener('keydown', (e) => this.handleKeyboard(e));

    // Detect mobile on resize
    window.addEventListener('resize', () => this.detectMobile());

    // Setup image click handlers for .img-popup links
    this.setupImageClickHandlers();
  }

  detectMobile() {
    this.isMobile = window.innerWidth <= 768;
  }

  setupImageClickHandlers() {
    // Get all image popup links (both .img-popup and .ajax-popup-link classes)
    const popupLinks = document.querySelectorAll('a.img-popup, a.ajax-popup-link');
    
    if (popupLinks.length === 0) {
      console.warn('ImageModal: No image popup links found');
      return;
    }

    console.log(`ImageModal: Found ${popupLinks.length} image popup links`);
    this.imageLinks = Array.from(popupLinks);
    
    // Add click listeners to popup links
    this.imageLinks.forEach((link, index) => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        console.log(`ImageModal: Opening image ${index + 1} of ${this.imageLinks.length}`);
        this.currentIndex = index;
        this.open();
      });

      // Also make the project image itself clickable
      const projectItem = link.closest('.project-item');
      if (projectItem) {
        const img = projectItem.querySelector('img');
        if (img) {
          img.style.cursor = 'pointer';
          img.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            console.log(`ImageModal: Opening image ${index + 1} of ${this.imageLinks.length} (via image click)`);
            this.currentIndex = index;
            this.open();
          });
        }
      }
    });
  }

  open() {
    if (this.imageLinks.length === 0) {
      console.warn('ImageModal: No images available to open');
      return;
    }

    this.modal.classList.add('active');
    this.modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';

    this.updateImage();
  }

  close() {
    this.modal.classList.remove('active');
    this.modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  updateImage() {
    if (this.imageLinks.length === 0 || !this.imageLinks[this.currentIndex]) {
      return;
    }
    
    const currentLink = this.imageLinks[this.currentIndex];
    const src = currentLink.getAttribute('href');
    
    // Get the image alt from the image inside the project item
    const img = currentLink.closest('.project-item')?.querySelector('img') || 
                currentLink.parentElement?.querySelector('img');
    const alt = img ? img.alt : 'Image';

    this.imgElement.src = src;
    this.imgElement.alt = alt;
    this.captionElement.textContent = alt;
    this.downloadBtn.href = src;

    // Update navigation button states
    this.updateNavigation();
  }

  updateNavigation() {
    if (this.prevBtn) {
      this.prevBtn.disabled = this.currentIndex === 0;
      this.prevBtn.setAttribute('aria-disabled', this.currentIndex === 0);
    }

    if (this.nextBtn) {
      this.nextBtn.disabled = this.currentIndex === this.imageLinks.length - 1;
      this.nextBtn.setAttribute('aria-disabled', this.currentIndex === this.imageLinks.length - 1);
    }
  }

  next() {
    if (this.currentIndex < this.imageLinks.length - 1) {
      this.currentIndex++;
      this.updateImage();
    }
  }

  previous() {
    if (this.currentIndex > 0) {
      this.currentIndex--;
      this.updateImage();
    }
  }

  handleKeyboard(e) {
    if (!this.modal.classList.contains('active')) return;

    switch (e.key) {
      case 'Escape':
        e.preventDefault();
        this.close();
        break;
      case 'ArrowLeft':
        e.preventDefault();
        this.previous();
        break;
      case 'ArrowRight':
        e.preventDefault();
        this.next();
        break;
    }
  }
}

// Initialize modal when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    console.log('ImageModal: Initializing on DOMContentLoaded');
    new ImageModal();
  });
} else {
  console.log('ImageModal: DOM already loaded, initializing now');
  new ImageModal();
}
