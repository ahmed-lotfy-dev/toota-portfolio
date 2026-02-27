import { Variants } from "framer-motion";

export const fadeUp: Variants = {
  hidden: { opacity: 0, y: 22 },
  show: {
    opacity: 1,
    y: 0,
    transition: { duration: 0.6, ease: "easeOut" },
  },
};

export const fadeUpDelayed = (delay = 0.12): Variants => ({
  hidden: { opacity: 0, y: 22 },
  show: {
    opacity: 1,
    y: 0,
    transition: { duration: 0.6, ease: "easeOut", delay },
  },
});

export const hoverLift = {
  rest: { y: 0 },
  hover: { y: -4, transition: { duration: 0.22, ease: [0.22, 1, 0.36, 1] as const } },
};
