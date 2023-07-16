FROM node:18-alpine

# Set the working directory
WORKDIR /Application-Test/frontend

# Copy package.json and package-lock.json
COPY package*.json ./

# Install Angular dependencies
RUN npm install

COPY . .

EXPOSE 4200
 
CMD ["npm", "start"]