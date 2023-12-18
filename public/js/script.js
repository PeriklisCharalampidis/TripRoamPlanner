////navbar///

let scrolled = false;

window.addEventListener('scroll', function() {
  let navbar = document.getElementById('navbar');
  let scrollPosition = window.scrollY;

  if (scrollPosition > 100) {
    navbar.classList.add('fixed');
    if (!scrolled) {
      navbar.style.transition = 'transform 0.2s';
      navbar.style.transform = 'translateY(-70px)';
      setTimeout(function() {
        navbar.style.transform = 'translateY(0)';
        scrolled = true;
      }, 200);
    }
  } else {
    navbar.classList.remove('fixed');
    scrolled = false; 
  }
});



///testimonials///

const testimonialsContainer = document.querySelector('.testimonials-container');
const testimonial = document.querySelector('.testimonial');
const userImage = document.querySelector('.user-image');
const username = document.querySelector('.username');
const role = document.querySelector('.role');

const testimonials = [
    {
        name: 'Miyah Smith',
        position: 'Experienced traveller',
        photo:
          'https://randomuser.me/api/portraits/women/90.jpg',
        text:
          "Effortlessly crafted my dream trip! From personalized itineraries to a tailored packing list, every detail was covered. Seamless and stress-free!",
      },
      {
        name: 'June Chang',
        position: 'Experienced traveller',
        photo: 'https://randomuser.me/api/portraits/women/44.jpg',
        text:
          'Creating my trip was a breeze! Loved the recommended activities and the freedom to add my own. Sharing my adventure journal was the cherry on top!',
      },
      {
        name: 'Lida Niskanen',
        position: 'Experienced traveller',
        photo: 'https://randomuser.me/api/portraits/women/68.jpg',
        text:
          "This trip planner is a game-changer! No research needed, just personalized suggestions and a packing list based on my destination. Easy, convenient, and fun!",
      },
      {
        name: 'Renee Sims',
        position: 'Experienced traveller',
        photo: 'https://randomuser.me/api/portraits/women/65.jpg',
        text:
          "Incredible experience! The site's recommendations perfectly complemented my planned activities. Sharing my trip journal on social media was a hit among my friends!",
      },
      {
        name: 'Jonathan Nunfiez',
        position: 'Experienced traveller',
        photo: 'https://randomuser.me/api/portraits/men/43.jpg',
        text:
          "Planning made easy! Designed my trip hassle-free, added my touches, and voila! The platform's packing list was a lifesaver. Seamless sharing on social media.",
      },
      {
        name: 'Sasha Ho',
        position: 'Experienced traveller',
        photo:
          'https://images.pexels.com/photos/415829/pexels-photo-415829.jpeg?h=350&auto=compress&cs=tinysrgb',
        text:
          "Personalized perfection! The suggested activities were spot-on, and adding my own made it truly mine. Sharing my trip journal with friends was a hit!",
      },
      {
        name: 'Michael Müller',
        position: 'Experienced traveller',
        photo: 'https://randomuser.me/api/portraits/men/97.jpg',
        text:
        "Efficient and fun! The platform curated a fantastic trip plan with activities galore. Sharing my journey on social media and the tailored packing list were fantastic bonuses!",
      },
]

let idx = 1;

function updateTestimonial() {
    const { name, position, photo, text } = testimonials[idx]
  
    testimonial.innerHTML = text
    userImage.src = photo
    username.innerHTML = name
    role.innerHTML = position
  
    idx++
  
    if (idx > testimonials.length - 1) {
      idx = 0
    }
  }
  
  setInterval(updateTestimonial, 10000)

////


// js to handle AJAX requests for increasing and decreasing counts for packing items list:
/*function increaseCount(tripId, tripPackingListItemId) {
  // Use AJAX to send a POST request to increase the count
  fetch(`/pakinglist/increase-count/${tripId}/${tripPackingListItemId}`, { method: 'GET' })
      .then(response => {
        if (response.ok) {
          // Reload the page after successful increase
          location.reload();
        }
      });
}*/
function increaseCount(myCount) {
  return myCount++;
}

function decreaseCount(tripId, tripPackingListItemId) {
  // Use AJAX to send a POST request to decrease the count
  fetch(`/pakinglist/decrease-count/${tripId}/${tripPackingListItemId}`, { method: 'GET' })
      .then(response => {
        if (response.ok) {
          // Reload the page after successful decrease
          location.reload();
        }
      });
}