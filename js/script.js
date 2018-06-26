// Define global variables used for contact form
var validName;
var validEmail;
var validMsg;

// Helper function to toggle contextual classes for erroneous and successful form input
function toggleValidationClasses(id, valid) {
  if(valid) {
    $(id).removeClass('has-error');
    $(id).addClass('has-success');

    $(id+' .form-control-feedback').removeClass('glyphicon-remove');
    $(id+' .form-control-feedback').addClass('glyphicon-ok');

    $(id+' small').addClass('invisible');
  }
  else {
    $(id).removeClass('has-success');
    $(id).addClass('has-error');

    // Only name and email input have glyphicon-remove feedback icons
    if(id != '#message-form-group') {
      $(id+' .form-control-feedback').removeClass('glyphicon-ok');
      $(id+' .form-control-feedback').addClass('glyphicon-remove');
    }

    $(id+' small').removeClass('invisible');
  }
}

// Helper function to reset contact form to original state
function resetForm() {
  // Reset name form group and name validity flag
  $('#name-form-group').removeClass('has-success');
  $('#name-form-group .form-control-feedback').removeClass('glyphicon-ok');
  $('#name').val("");
  validName = false;

  // Reset email form group and email validity flag
  $('#email-form-group').removeClass('has-success');
  $('#email-form-group .form-control-feedback').removeClass('glyphicon-ok');
  $('#email').val("");
  validEmail = false;

  // Reset message form group and message validity flag
  $('#message-form-group').removeClass('has-success');
  $('#message-form-group .form-control-feedback').removeClass('glyphicon-ok');
  $('#message').val("");
  validMsg = false;
}

// Adds functionality, logic, and validation to contact form
function functionalizeForm() {
  // Define regex
  var regexName = /^[a-zA-Z\s]+$/;
  var regexEmail = /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/; // e-mail regex from W3C

  $('#name').on({
    'focusout' : function() {
      if(this.value) {
        if (regexName.test(this.value)) {
          toggleValidationClasses('#name-form-group', true);
          validName = true;
        }
        else {
          toggleValidationClasses('#name-form-group', false);
          validName = false;
        }
      }
      else if($('#name-form-group').hasClass('has-error') || $('#name-form-group').hasClass('has-success')) {
        toggleValidationClasses('#name-form-group', false);
        validName = false;
      }
    }
  });

  $('#email').on({
    'focusout' : function() {
      if(this.value) {
        if (regexEmail.test(this.value)) {
          toggleValidationClasses('#email-form-group', true);
          validEmail = true;
        }
        else {
          toggleValidationClasses('#email-form-group', false);
          validEmail = false;
        }
      }
      else if($('#email-form-group').hasClass('has-error') || $('#email-form-group').hasClass('has-success')) {
        toggleValidationClasses('#email-form-group', false);
        validEmail = false;
      }
    }
  });

  $('#message').on({
    'focusout' : function() {
      if(this.value) {
        toggleValidationClasses('#message-form-group', true);
        validMsg = true;
      }
      else if($('#message-form-group').hasClass('has-error') || $('#message-form-group').hasClass('has-success')) {
        toggleValidationClasses('#message-form-group', false);
        validMsg = false;
      }
    }
  });

  $('#submit').on({
    'click' : function() {
      var name = $('#name').val();
      var email = $('#email').val();
      var message = $('#message').val();
      if(validName && validEmail && validMsg) {
        $.ajax({
          url: 'http://aileenagravante.com/scripts/contact.php',
          method: 'POST',
          data: {
            name: name,
            email: email,
            message: message
          },
          success : function() {
            alert("Woo hoo! Thanks for saying \"hello\". We'll get back to you as soon as possible.")
            resetForm();
          },
          failure : function() {
            alert("Oops, errors happen. This one's on us. Please try again later.")
          },
        });
      }
      else {
        if(!name) {
          toggleValidationClasses('#name-form-group', false);
        }
        if(!email) {
          toggleValidationClasses('#email-form-group', false);
        }
        if(!message) {
          toggleValidationClasses('#message-form-group', false);
        }
      }
    }
  });
}

// Helper function to toggle hiding and showing of mobile menu and toggling
//    appropriate icons for the mobile menu button
function toggleMobileMenu(action) {
  if(action == 'show') {
    $('#mobile-nav-container').show("slow");
    $('#menu-button i').removeClass('fa-bars');
    $('#menu-button i').addClass('fa-times');
  }
  else {
    $('#mobile-nav-container').hide("slow");
    $('#menu-button i').removeClass('fa-times');
    $('#menu-button i').addClass('fa-bars');
  }
}

// Adds functionality to navigation
function functionalizeNavigation() {
  // Smooth scroll to anchor div
  $('a[href^="#"]').on({
    'click' : function(event) {
      // We don't want this functionality for the carousel, navigational links only
      if(!($(this).hasClass('carousel-control'))) {
        event.preventDefault();
        // If we're clicking the home button or a link in the mobile navigation, hide the mobile navigation
        if($(this).parents('#mobile-nav-container').length || $(this).has('.home-button')) {
          toggleMobileMenu('hide');
        }
        console.log($('#desktop-nav-container').outerHeight());
        $('html, body').animate({
          scrollTop : $(this.hash).offset().top-50
        }, 1200);
        // Since we're preventing the default for links, add the hash corresponding to the URL
        window.location.hash = this.hash;
      }
    }
  });
  $('#menu-button').on({
    'click' : function(event) {
      event.preventDefault();
      if($('#mobile-nav-container').css('display') == 'block') {
        toggleMobileMenu('hide');
      }
      else {
        toggleMobileMenu('show');
      }
    }
  });
}

// Helper function to add active class to active nav item and remove from other nav items
function activateNavItem(target) {
  // Since the home nav item also exists in the mobile-buttons-container, we add this if/else block
  if(target == '#home') {
    $('#mobile-buttons-container .home-button a').addClass('active');
  }
  else {
    $('#mobile-buttons-container .home-button a').removeClass('active');
  }

  $('#desktop-nav-container li a').each(function(index, element) {
    if(target == $(element).attr('href')) {
      $(element).addClass('active');
    }
    else {
      $(element).removeClass('active');
    }
  });

  $('#mobile-nav-container li a').each(function(index, element) {
    if(target == $(element).attr('href')) {
      $(element).addClass('active');
    }
    else {
      $(element).removeClass('active');
    }
  });
}

function activateNavItemOnScroll() {
  var windowTop = ($(window).scrollTop() + 50);

  if(windowTop < $('#about').position().top) {
    activateNavItem('#home');
  }
  else if((windowTop >= $('#about').position().top) && (windowTop < $('#parallax-bg-2').position().top)) {
    activateNavItem('#about');
  }
  else if((windowTop >= $('#parallax-bg-2').position().top) && (windowTop < $('#parallax-bg-3').position().top)) {
    activateNavItem('#featured-project');
  }
  else {
    activateNavItem('#contact');
  }
}

// Helper function that allows demo button for each phase to play HTML video and the exit button on each
//    modal pause the video
// We use JavaScript since play() and pause() are functions of the DOM not jQuery
function functionalizeModalButtons(phase) {
  $('#'+phase+'-demo').on({
    'click' : function() {
      document.getElementById(phase+'-video').play();
    }
  })

  $('#'+phase+'-modal button').on({
    'click' : function() {
      document.getElementById(phase+'-video').pause();
    }
  })
}

// Helper function to functionalize modal buttons for all videos
function functionalizeVideoButtons() {
  functionalizeModalButtons('phase-1');
  functionalizeModalButtons('phase-2-desktop');
  functionalizeModalButtons('phase-2-mobile');
  functionalizeModalButtons('phase-3');
}

// When document is ready, run these functions
$(function() {
  functionalizeNavigation();
  resetForm();
  functionalizeForm();
  activateNavItem('#home');
  functionalizeVideoButtons();
});

$(window).scroll(function() {
  activateNavItemOnScroll();
});

// Take care of the case when the width of the window is resized while the mobile nav is expanded
$(window).resize(function() {
  if(($('#desktop-nav-container').css('display') == 'block') && ($('#mobile-nav-container').css('display') == 'block')) {
    toggleMobileMenu('hide');
  }
});
