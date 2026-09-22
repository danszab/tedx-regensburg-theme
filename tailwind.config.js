/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php",
    "./inc/**/*.php",
    "./template-parts/**/*.php",
    "./assets/js/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        'tedx-red': '#eb0028',
        'tedx-red-hover': '#c80022',
        'tedx-red-dark': '#99001a',
        'tedx-green': '#2ad17e',
        'tedx-green-dark': '#22a865',
        'tedx-dark': '#0a0a0a',
        'tedx-surface': '#121212',
        'tedx-panel': '#181818',
        'tedx-card': '#252525',
        'tedx-button': '#212121',
        'tedx-border': '#555555',
        'tedx-outline': '#938f99',
        'tedx-muted': '#a9a9a9',
        'tedx-light': '#dedede',
        'tedx-gold': '#d19f2a'
      },
      fontFamily: {
        sans: ['"Helvetica Neue"', 'Inter', 'sans-serif'],
        display: ['"Helvetica Neue"', 'Inter', 'sans-serif'],
        body: ['"Helvetica Neue"', 'Inter', 'sans-serif'],
      },
      maxWidth: {
        'figma': '1000px',
        'figma-wide': '1512px',
      },
      borderRadius: {
        '3xl': '24px',
        '4xl': '32px',
        '5xl': '48px',
      },
      boxShadow: {
        'glow-red': '0 0 40px -10px rgba(235, 0, 40, 0.4)',
        'glow-green': '0 0 40px -10px rgba(42, 209, 126, 0.3)',
      },
      letterSpacing: {
        'tightest': '-0.05em',
        'tighter': '-0.03em',
        'tight': '-0.02em',
        'wide-tag': '0.05em',
      }
    },
  },
  plugins: [],
}
