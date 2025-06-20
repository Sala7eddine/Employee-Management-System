module.exports = {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
    ],
    theme: {
      extend: {
        fontFamily: {
          poppins: ['Poppins', 'sans-serif'],
        },
        colors: {
          primary: '#4f46e5',
          secondary: '#10b981',
          dark: '#1e293b',
        }
      },
    },
    plugins: [],
  }