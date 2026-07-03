const products = [
  {
    id: 1,
    title: "500 Million Subscribers Tee",
    price: "$24.99",
    priceFrom: true,
    badges: ["new", "limited"],
    colors: ["#000000", "#ffffff", "#ff4d00"],
    images: [
      "https://picsum.photos/seed/tee1a/600/600",
      "https://picsum.photos/seed/tee1b/600/600"
    ],
    description: "Celebrate 500 million subscribers with this exclusive collector's edition tee. Premium cotton construction with bold front graphic.",
    reviews: { rating: 4.8, count: 124 },
    sizes: ["SM", "MD", "LG", "XL", "2XL"],
    category: "tees",
    type: "T-Shirt"
  },
  {
    id: 2,
    title: "500 Million Subscribers Hoodie",
    price: "$44.99",
    priceFrom: true,
    badges: ["new", "limited"],
    colors: ["#000000", "#ffffff"],
    images: [
      "https://picsum.photos/seed/hoodie1a/600/600",
      "https://picsum.photos/seed/hoodie1b/600/600"
    ],
    description: "Limited edition 500M celebration hoodie. Heavyweight fleece with embroidered details and custom drawcords.",
    reviews: { rating: 4.9, count: 89 },
    sizes: ["SM", "MD", "LG", "XL", "2XL", "3XL"],
    category: "hoodies",
    type: "Hoodies & Sweatshirts"
  },
  {
    id: 3,
    title: "Beast Vibes Wave Tee",
    price: "$24.99",
    priceFrom: false,
    badges: ["new"],
    colors: ["#0047ab", "#000000", "#ffffff"],
    images: [
      "https://picsum.photos/seed/beastvibes1a/600/600",
      "https://picsum.photos/seed/beastvibes1b/600/600"
    ],
    description: "Catch the wave with this summer-inspired tee. Soft cotton blend with a unique screen-printed design.",
    reviews: { rating: 4.5, count: 56 },
    sizes: ["SM", "MD", "LG", "XL", "2XL"],
    category: "tees",
    type: "T-Shirt"
  },
  {
    id: 4,
    title: "Panther Print Board Shorts",
    price: "$34.99",
    priceFrom: true,
    badges: ["new"],
    colors: ["#1a3a5c", "#000000"],
    images: [
      "https://picsum.photos/seed/shorts1a/600/600",
      "https://picsum.photos/seed/shorts1b/600/600"
    ],
    description: "Make a statement with these all-over print board shorts. Quick-dry fabric with elastic waistband and mesh lining.",
    reviews: { rating: 4.3, count: 28 },
    sizes: ["SM", "MD", "LG", "XL", "2XL"],
    category: "shorts",
    type: "Shorts & Pants"
  },
  {
    id: 5,
    title: "Authentic Beast Edition Baseball Jersey",
    price: "$59.99",
    priceFrom: true,
    badges: ["restock", "trending"],
    colors: ["#ffffff", "#000000"],
    images: [
      "https://picsum.photos/seed/jersey1a/600/600",
      "https://picsum.photos/seed/jersey1b/600/600"
    ],
    description: "Official Beast Athletics baseball jersey. Mesh fabric with moisture-wicking technology and customized号码.",
    reviews: { rating: 4.7, count: 92 },
    sizes: ["SM", "MD", "LG", "XL", "2XL", "3XL"],
    category: "jerseys",
    type: "Jersey"
  },
  {
    id: 6,
    title: "MB Panther Baseball Shorts",
    price: "$34.99",
    priceFrom: false,
    badges: ["restock", "trending"],
    colors: ["#000000"],
    images: [
      "https://picsum.photos/seed/panthershorts1a/600/600",
      "https://picsum.photos/seed/panthershorts1b/600/600"
    ],
    description: "Performance baseball shorts with panther graphic. Built-in compression liner and zippered pockets.",
    reviews: { rating: 4.6, count: 145 },
    sizes: ["SM", "MD", "LG", "XL", "2XL"],
    category: "shorts",
    type: "Shorts & Pants"
  },
  {
    id: 7,
    title: "Glow in the Dark Beast Socks",
    price: "$9.99",
    priceFrom: false,
    badges: ["new", "trending"],
    colors: ["#00ff00"],
    images: [
      "https://picsum.photos/seed/socks1a/600/600",
      "https://picsum.photos/seed/socks1b/600/600"
    ],
    description: "Light up the night with these glow-in-the-dark crew socks. Comfort knit with reinforced heel and toe.",
    reviews: { rating: 4.4, count: 37 },
    sizes: ["One Size"],
    category: "accessories",
    type: "Accessories"
  },
  {
    id: 8,
    title: "Beast Edition Baseball - 2 Pack",
    price: "$12.99",
    priceFrom: false,
    badges: ["restock", "trending"],
    colors: [],
    images: [
      "https://picsum.photos/seed/baseball1a/600/600",
      "https://picsum.photos/seed/baseball1b/600/600"
    ],
    description: "Official MrBeast baseballs. Regulation size with Beast logo. Perfect for backyard practice or display.",
    reviews: { rating: 4.2, count: 63 },
    sizes: ["One Size"],
    category: "accessories",
    type: "Accessories"
  },
  {
    id: 9,
    title: "Beast Vibes Wave Board Shorts",
    price: "$34.99",
    priceFrom: true,
    badges: ["new"],
    colors: ["#0047ab", "#ff6b35", "#000000"],
    images: [
      "https://picsum.photos/seed/waveshorts1a/600/600",
      "https://picsum.photos/seed/waveshorts1b/600/600"
    ],
    description: "Summer-ready board shorts with wave pattern. 4-way stretch fabric with zip closure and drainage mesh.",
    reviews: { rating: 4.5, count: 41 },
    sizes: ["SM", "MD", "LG", "XL", "2XL"],
    category: "shorts",
    type: "Shorts & Pants"
  },
  {
    id: 10,
    title: "Beast FC Soccer Jersey",
    price: "$44.99",
    priceFrom: true,
    badges: ["new", "trending"],
    colors: ["#0047ab", "#ffffff"],
    images: [
      "https://picsum.photos/seed/soccerjersey1a/600/600",
      "https://picsum.photos/seed/soccerjersey1b/600/600"
    ],
    description: "Represent Beast FC in this premium soccer jersey. Breathable fabric with moisture management and official crest.",
    reviews: { rating: 4.8, count: 78 },
    sizes: ["SM", "MD", "LG", "XL", "2XL", "3XL"],
    category: "jerseys",
    type: "Jersey"
  },
  {
    id: 11,
    title: "All Summer Long Water Bottle",
    price: "$12.99",
    priceFrom: false,
    badges: ["new"],
    colors: [],
    images: [
      "https://picsum.photos/seed/bottle1a/600/600",
      "https://picsum.photos/seed/bottle1b/600/600"
    ],
    description: "Stay hydrated in style. Double-wall insulated stainless steel. Keeps drinks cold for 24 hours.",
    reviews: { rating: 4.6, count: 22 },
    sizes: ["One Size"],
    category: "accessories",
    type: "Accessories"
  },
  {
    id: 12,
    title: "Beast FC Soccer Shorts",
    price: "$34.99",
    priceFrom: true,
    badges: ["new", "trending"],
    colors: ["#0047ab", "#000000"],
    images: [
      "https://picsum.photos/seed/soccershorts1a/600/600",
      "https://picsum.photos/seed/soccershorts1b/600/600"
    ],
    description: "Beast FC match shorts. Lightweight performance fabric with elastic waist and team crest.",
    reviews: { rating: 4.3, count: 15 },
    sizes: ["SM", "MD", "LG", "XL", "2XL"],
    category: "shorts",
    type: "Shorts & Pants"
  },
  {
    id: 13,
    title: "Beast Edition Plate Hoodie",
    price: "$44.99",
    priceFrom: true,
    badges: ["new", "trending"],
    colors: ["#000000", "#ffffff"],
    images: [
      "https://picsum.photos/seed/platehoodie1a/600/600",
      "https://picsum.photos/seed/platehoodie1b/600/600"
    ],
    description: "Bold plate graphic hoodie. Heavyweight cotton fleece with ribbed cuffs and hem. Oversized fit.",
    reviews: { rating: 4.7, count: 104 },
    sizes: ["SM", "MD", "LG", "XL", "2XL", "3XL"],
    category: "hoodies",
    type: "Hoodies & Sweatshirts"
  },
  {
    id: 14,
    title: "Glow In The Dark Panther Tee",
    price: "$24.99",
    priceFrom: true,
    badges: [],
    colors: ["#000000"],
    images: [
      "https://picsum.photos/seed/glowtee1a/600/600",
      "https://picsum.photos/seed/glowtee1b/600/600"
    ],
    description: "Panther graphic that glows in the dark. Ultra-soft ringspun cotton. A fan favorite.",
    reviews: { rating: 4.5, count: 210 },
    sizes: ["SM", "MD", "LG", "XL", "2XL"],
    category: "tees",
    type: "T-Shirt"
  },
  {
    id: 15,
    title: "MrBeast Panther Hat - Black",
    price: "$24.99",
    priceFrom: false,
    badges: ["trending"],
    colors: ["#000000", "#0047ab"],
    images: [
      "https://picsum.photos/seed/hat1a/600/600",
      "https://picsum.photos/seed/hat1b/600/600"
    ],
    description: "Premium embroidered panther cap. Structured fit with curved brim and adjustable snapback closure.",
    reviews: { rating: 4.6, count: 88 },
    sizes: ["One Size"],
    category: "hats",
    type: "Hat"
  },
  {
    id: 16,
    title: "Beast Athletics Flame Shorts",
    price: "$34.99",
    priceFrom: false,
    badges: ["new", "trending"],
    colors: ["#ff6b35", "#000000"],
    images: [
      "https://picsum.photos/seed/flameshorts1a/600/600",
      "https://picsum.photos/seed/flameshorts1b/600/600"
    ],
    description: "Flame graphic training shorts. Performance fabric with interior pocket and Beast Athletics branding.",
    reviews: { rating: 4.4, count: 55 },
    sizes: ["SM", "MD", "LG", "XL", "2XL"],
    category: "shorts",
    type: "Shorts & Pants"
  }
];
